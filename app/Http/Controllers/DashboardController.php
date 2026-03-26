<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\FeeStructure;
use App\Models\Payment;
use App\Models\Student;
use App\Models\TimetableSlot;
use App\Models\User;
use App\Settings\SchoolSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $settings    = app(SchoolSettings::class);
        $user        = $request->user();
        $academicYear = AcademicYear::where('is_active', true)->first();

        // ── Stats principales ─────────────────────────────────────────────
        $stats = $this->buildStats($academicYear);

        // ── Alertes ───────────────────────────────────────────────────────
        $alerts = $this->buildAlerts($academicYear);

        // ── Paiements récents (5 derniers) ────────────────────────────────
        $recentPayments = [];
        if ($user->can('payments.view') && $academicYear) {
            $recentPayments = Payment::with(['enrollment.student', 'enrollment.classroom'])
                ->whereHas('enrollment', fn ($q) =>
                    $q->where('academic_year_id', $academicYear->id)
                )
                ->latest('payment_date')
                ->take(5)
                ->get()
                ->map(fn ($p) => [
                    'id'             => $p->id,
                    'student_name'   => $p->enrollment->student->full_name ?? '—',
                    'classroom_name' => $p->enrollment->classroom->name ?? '—',
                    'amount'         => $p->amount,
                    'payment_type'   => $p->payment_type,
                    'payment_date'   => $p->payment_date->format('d/m/Y'),
                    'receipt_number' => $p->receipt_number,
                ]);
        }

        // ── Élèves récemment inscrits (5 derniers) ────────────────────────
        $recentStudents = [];
        if ($user->can('students.view') && $academicYear) {
            $recentStudents = Enrollment::with(['student', 'classroom'])
                ->where('academic_year_id', $academicYear->id)
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($e) => [
                    'id'             => $e->student->id,
                    'full_name'      => $e->student->full_name ?? '—',
                    'matricule'      => $e->student->matricule,
                    'classroom_name' => $e->classroom->name ?? '—',
                    'enrolled_at'    => $e->created_at->format('d/m/Y'),
                ]);
        }

        return Inertia::render('Dashboard/Index', [
            'academicYear'   => $academicYear ? [
                'id'    => $academicYear->id,
                'label' => $academicYear->label,
            ] : null,
            'stats'          => $stats,
            'alerts'         => $alerts,
            'recentPayments' => $recentPayments,
            'recentStudents' => $recentStudents,
        ]);
    }

    // ── Statistiques ──────────────────────────────────────────────────────

    private function buildStats(?AcademicYear $year): array
    {
        if (! $year) {
            return [
                'students'    => 0,
                'classrooms'  => 0,
                'staff'       => 0,
                'paid_today'  => 0,
                'unpaid'      => 0,
                'absences_month' => 0,
            ];
        }

        // Élèves inscrits cette année
        $studentCount = Enrollment::where('academic_year_id', $year->id)
            ->where('status', 'actif')
            ->count();

        // Classes actives
        $classroomCount = Classroom::where('academic_year_id', $year->id)->count();

        // Enseignants actifs
        $staffCount = User::role('Enseignant')->where('is_active', true)->count();

        // Paiements du jour (FCFA)
        $paidToday = Payment::whereDate('payment_date', today())
            ->sum('amount');

        // Inscriptions avec paiement en retard
        // (trimestre dépassé mais pas encore payé)
        $unpaidCount = $this->countUnpaidEnrollments($year);

        // Absences non justifiées ce mois
        $absencesMonth = Absence::where('absenceable_type', 'App\\Models\\Student')
            ->where('is_justified', false)
            ->whereMonth('absent_date', now()->month)
            ->whereYear('absent_date', now()->year)
            ->count();

        return [
            'students'       => $studentCount,
            'classrooms'     => $classroomCount,
            'staff'          => $staffCount,
            'paid_today'     => $paidToday,
            'unpaid'         => $unpaidCount,
            'absences_month' => $absencesMonth,
        ];
    }

    private function countUnpaidEnrollments(AcademicYear $year): int
    {
        // Enrollments actifs sans au moins un paiement
        return Enrollment::where('academic_year_id', $year->id)
            ->where('status', 'actif')
            ->whereDoesntHave('payments')
            ->count();
    }

    // ── Alertes ───────────────────────────────────────────────────────────

    private function buildAlerts(?AcademicYear $year): array
    {
        $alerts = [];

        if (! $year) {
            $alerts[] = [
                'type'    => 'warning',
                'message' => 'Aucune année scolaire active. Créez-en une dans les paramètres.',
                'link'    => null,
            ];
            return $alerts;
        }

        // 1. Élèves avec 3+ absences non justifiées ce mois
        $highAbsences = Absence::select('absenceable_id', DB::raw('COUNT(*) as total'))
            ->where('absenceable_type', 'App\\Models\\Student')
            ->where('is_justified', false)
            ->whereMonth('absent_date', now()->month)
            ->groupBy('absenceable_id')
            ->having('total', '>=', 3)
            ->count();

        if ($highAbsences > 0) {
            $alerts[] = [
                'type'    => 'warning',
                'message' => "{$highAbsences} élève(s) avec 3 absences non justifiées ou plus ce mois.",
                'link'    => route('absences.index'),
            ];
        }

        // 2. Élèves avec paiement en retard
        $unpaid = $this->countUnpaidEnrollments($year);
        if ($unpaid > 0) {
            $alerts[] = [
                'type'    => 'error',
                'message' => "{$unpaid} inscription(s) sans aucun paiement enregistré.",
                'link'    => route('payments.index'),
            ];
        }

        // 3. Classes sans emploi du temps configuré
        $noTimetable = Classroom::where('academic_year_id', $year->id)
            ->whereDoesntHave('timetableSlots')
            ->count();

        if ($noTimetable > 0) {
            $alerts[] = [
                'type'    => 'info',
                'message' => "{$noTimetable} classe(s) sans emploi du temps configuré.",
                'link'    => route('timetable.index'),
            ];
        }

        // 4. Aucune alerte
        if (empty($alerts)) {
            $alerts[] = [
                'type'    => 'success',
                'message' => 'Tout est en ordre. Bonne journée !',
                'link'    => null,
            ];
        }

        return $alerts;
    }
}