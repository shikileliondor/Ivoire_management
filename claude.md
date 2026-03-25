
🏫 EduGest CI — Système de Gestion Scolaire

Fichier de contexte Claude Code — À placer à la racine du projet


📌 Vue d'ensemble du projet
Nom du produit : EduGest CI
Type : Application web de gestion administrative scolaire
Cible : Écoles primaires privées ivoiriennes
Hébergement : LWS mutualisé (PHP 8.2+, MySQL 8, pas de Node.js en prod)
Langue de l'interface : Français
Système scolaire : MENA Côte d'Ivoire (3 trimestres)

🏗️ Stack technique
Backend

Laravel 12, PHP 8.2+
Inertia.js (pont Laravel ↔ Vue 3, pas d'API REST)
Laravel Sanctum (authentification)
Spatie Permission ^6 (rôles)
Spatie Settings ^3 (configuration école)
Spatie Activity Log ^4 (journal d'audit)
Spatie Backup ^9 (sauvegarde)
barryvdh/laravel-dompdf ^3 (PDF)
maatwebsite/excel ^3 (exports)
intervention/image-laravel ^1 (photos)
haruncpi/laravel-id-generator ^1 (matricules)
propaganistas/laravel-phone ^6 (téléphones +225)
predis/predis ^3 (Redis)
tightenco/ziggy ^2 (routes dans Vue)
knuckleswtf/scribe ^5 (doc API)

Frontend

Vue 3 (Composition API, script setup)
Tailwind CSS 4
FullCalendar Vue 3 ^6 (emplois du temps)
@vueuse/core ^13
vue-toastification ^2
Vite 7


👥 Rôles utilisateurs
Directeur   → Accès total (lecture + écriture + validation + configuration)
Secrétaire  → Élèves, inscriptions, paiements, absences, documents
Enseignant  → Ses classes uniquement (notes, absences, emploi du temps)
Autorisations gérées via Policies Laravel + Spatie Permission.
Chaque route sensible protégée par middleware role: ou permission:.

🗂️ Structure des dossiers
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── StudentController.php
│   │   ├── ParentGuardianController.php
│   │   ├── EnrollmentController.php
│   │   ├── ClassroomController.php
│   │   ├── SubjectController.php
│   │   ├── GradeController.php
│   │   ├── GradePeriodController.php
│   │   ├── TimetableController.php
│   │   ├── AbsenceController.php
│   │   ├── PaymentController.php
│   │   ├── FeeStructureController.php
│   │   ├── StaffController.php
│   │   ├── AcademicYearController.php
│   │   ├── ReportController.php
│   │   └── Settings/
│   │       └── SchoolSettingsController.php
│   ├── Requests/
│   │   ├── StoreStudentRequest.php
│   │   ├── UpdateStudentRequest.php
│   │   ├── StoreGradeRequest.php
│   │   ├── StorePaymentRequest.php
│   │   └── ...
│   └── Middleware/
│       └── EnsureSchoolIsConfigured.php
├── Models/
│   ├── User.php
│   ├── AcademicYear.php
│   ├── Level.php
│   ├── Classroom.php
│   ├── Student.php
│   ├── ParentGuardian.php
│   ├── EmergencyContact.php
│   ├── StudentDocument.php
│   ├── StudentVaccination.php
│   ├── StudentAcademicHistory.php
│   ├── Enrollment.php
│   ├── Subject.php
│   ├── GradingConfig.php
│   ├── GradePeriod.php
│   ├── Grade.php
│   ├── TimetableSlot.php
│   ├── Absence.php
│   ├── FeeStructure.php
│   └── Payment.php
├── Services/
│   ├── GradeCalculatorService.php
│   ├── BulletinService.php
│   ├── ReceiptService.php
│   ├── CertificateService.php
│   ├── MatriculeService.php
│   └── TimetableConflictService.php
├── Policies/
│   ├── StudentPolicy.php
│   ├── GradePolicy.php
│   ├── PaymentPolicy.php
│   └── ...
└── Settings/
    └── SchoolSettings.php

resources/js/
├── app.js
├── Components/
│   ├── UI/
│   │   ├── AppButton.vue
│   │   ├── AppInput.vue
│   │   ├── AppModal.vue
│   │   ├── AppBadge.vue
│   │   ├── AppCard.vue
│   │   └── AppPagination.vue
│   ├── Forms/
│   │   ├── FormField.vue
│   │   ├── PhoneInput.vue
│   │   └── FileUpload.vue
│   ├── Tables/
│   │   ├── DataTable.vue
│   │   └── TableActions.vue
│   └── Dashboard/
│       ├── StatCard.vue
│       ├── PaymentProgressBar.vue
│       └── AlertWidget.vue
├── Layouts/
│   ├── AppLayout.vue
│   └── AuthLayout.vue
└── Pages/
    ├── Auth/Login.vue
    ├── Dashboard/Index.vue
    ├── Students/
    │   ├── Index.vue
    │   ├── Create.vue
    │   ├── Edit.vue
    │   └── Show.vue
    ├── Staff/
    ├── Classrooms/
    ├── Grades/
    ├── Timetable/
    ├── Absences/
    ├── Payments/
    ├── Reports/
    └── Settings/

🗃️ Base de données
Conventions


### Migrations réellement présentes (Laravel + métier)
- **Migrations Laravel de base** :
  - `0001_01_01_000000_create_users_table.php`
  - `0001_01_01_000001_create_cache_table.php`
  - `0001_01_01_000002_create_jobs_table.php`
- **RBAC (Spatie Permission)** :
  - `2026_03_25_201953_create_permission_tables.php`
- **Migrations métier école** :
  - `2026_03_25_210000_create_academic_years_table.php`
  - `2026_03_25_210001_create_grading_configs_table.php`
  - `2026_03_25_210002_create_levels_table.php`
  - `2026_03_25_210003_create_classrooms_table.php`
  - `2026_03_25_210004_create_subjects_table.php`
  - `2026_03_25_210005_create_classroom_subject_table.php`
  - `2026_03_25_210006_create_students_table.php`
  - `2026_03_25_210007_create_parents_guardians_table.php`
  - `2026_03_25_210008_create_student_parent_table.php`
  - `2026_03_25_210009_create_emergency_contacts_table.php`
  - `2026_03_25_210010_create_student_documents_table.php`
  - `2026_03_25_210011_create_student_vaccinations_table.php`
  - `2026_03_25_210012_create_student_academic_history_table.php`
  - `2026_03_25_210013_create_enrollments_table.php`
  - `2026_03_25_210014_create_grade_periods_table.php`
  - `2026_03_25_210015_create_grades_table.php`
  - `2026_03_25_210016_create_timetable_slots_table.php`
  - `2026_03_25_210017_create_absences_table.php`
  - `2026_03_25_210018_create_fee_structures_table.php`
  - `2026_03_25_210019_create_payments_table.php`

### Models réellement présents dans `app/Models`
- `User`
- `AcademicYear`
- `GradingConfig`
- `Level`
- `Classroom`
- `Subject`
- `Student`
- `ParentGuardian`
- `EmergencyContact`
- `StudentDocument`
- `StudentVaccination`
- `StudentAcademicHistory`
- `Enrollment`
- `GradePeriod`
- `Grade`
- `TimetableSlot`
- `Absence`
- `FeeStructure`
- `Payment`


Tables en snake_case anglais (standard Laravel)
Clés étrangères : table_id
Soft deletes sur : students, enrollments, payments

Schéma complet
academic_years
  id, label, start_date, end_date, is_active, timestamps

grading_configs
  id, academic_year_id, homework_weight, exam_weight, timestamps

users
  id, name, email, password, phone, photo, is_active, timestamps
  → Rôles via Spatie : Directeur | Secrétaire | Enseignant

levels
  id, name (CP1/CP2/CE1/CE2/CM1/CM2), order, timestamps

classrooms
  id, academic_year_id, level_id, name, max_students, timestamps

classroom_subject [pivot]
  id, classroom_id, subject_id, user_id, timestamps

subjects
  id, name, code, coefficient, is_active, timestamps

students
  id, matricule (unique)
  -- Identité
  last_name, first_name, birth_date, birth_place, birth_country
  birth_certificate_number, birth_certificate_date, birth_certificate_place
  gender, nationality
  -- Résidence
  address, city, district, country
  -- Santé
  blood_type, has_disability, disability_description
  chronic_illness, allergies, medical_notes
  -- Situation familiale
  family_situation, siblings_count, rank_in_siblings
  -- Scolarité antérieure
  previous_school, previous_school_city, previous_class, previous_year
  admission_type (nouveau|redoublant|transféré), has_transfer_certificate
  -- Médias & Statut
  photo, is_active, timestamps, deleted_at

parents_guardians
  id, last_name, first_name, gender, birth_date, nationality
  phone, phone_2, email, whatsapp
  relationship, profession, employer, workplace_phone
  address, city, district
  is_alive, is_legal_guardian, timestamps

student_parent [pivot]
  student_id, parent_id
  is_primary_contact, is_financial_responsible, is_pickup_authorized

emergency_contacts
  id, student_id, name, relationship, phone, phone_2, priority, timestamps

student_documents
  id, student_id, document_type, label, file_path
  is_original, received_date, notes, timestamps

student_vaccinations
  id, student_id, vaccine_name, dose_number
  vaccination_date, next_due_date, administered_by, timestamps

student_academic_history
  id, student_id, academic_year, school_name, class_name
  result, average, rank, notes, timestamps

enrollments
  id, student_id, classroom_id, academic_year_id
  enrollment_date, status, timestamps, deleted_at

grade_periods
  id, academic_year_id, classroom_id, subject_id
  trimester (1|2|3), type (devoir|composition)
  label, date, max_score (défaut: 20), timestamps

grades
  id, enrollment_id, grade_period_id
  score (decimal 5,2), is_absent, comment
  graded_by (user_id), timestamps

timetable_slots
  id, classroom_id, subject_id, user_id
  academic_year_id, day_of_week (1=Lundi...6=Samedi)
  start_time, end_time, timestamps

absences
  id, absenceable_id, absenceable_type (Student|User)
  absent_date, reason, is_justified
  justified_by (user_id), notes, timestamps

fee_structures
  id, academic_year_id, level_id
  registration_fee, trimester_1_fee, trimester_2_fee, trimester_3_fee
  timestamps

payments
  id, enrollment_id, receipt_number (unique)
  amount, payment_date
  payment_type (inscription|tranche_1|tranche_2|tranche_3)
  payment_method (espèces|mobile_money|chèque|virement)
  received_by (user_id), notes, timestamps, deleted_at

⚙️ SchoolSettings (Spatie Settings)
phpclass SchoolSettings extends Settings
{
    public string $name = '';
    public string $logo = '';
    public string $address = '';
    public string $phone = '';
    public string $email = '';
    public string $slogan = '';
    public string $director_name = '';
    public string $current_academic_year_id = '';

    public static function group(): string
    {
        return 'school';
    }
}
```

---

## 🧮 Calcul des moyennes — Règles métier
```
JAMAIS stocker les moyennes en base → toujours calculer dynamiquement.

Moyenne trimestrielle par matière :
  1. Récupérer tous les grade_periods de type 'devoir' du trimestre
  2. Calculer la moyenne des devoirs
  3. Récupérer la note de composition du trimestre
  4. Appliquer la formule :
     (moy_devoirs × homework_weight + composition × exam_weight)
     / (homework_weight + exam_weight)
  5. La pondération vient de grading_configs

Moyenne générale du trimestre :
  Somme(moyenne_matière × coefficient) / Somme(coefficients)

Rang :
  Calculé sur moyenne générale — ex-aequo autorisé
```

Encapsulé dans `GradeCalculatorService`.

---

## 🔒 Règles d'autorisation
```
Directeur
  → Tout lire, tout modifier, valider bulletins, accéder aux paramètres

Secrétaire
  → CRUD élèves, parents, inscriptions, paiements, absences
  → Générer certificats et listes de classe
  → ✗ Pas accès aux notes ni aux paramètres

Enseignant
  → Voir uniquement ses classes (via classroom_subject)
  → Saisir notes de ses matières uniquement
  → Voir et saisir absences de ses élèves
  → Voir son emploi du temps
  → ✗ Pas accès aux paiements ni aux paramètres
```

---

## 📄 Documents PDF

| Document | Service | Template |
|---|---|---|
| Bulletin trimestriel | BulletinService | bulletins/trimester.blade.php |
| Reçu de paiement | ReceiptService | receipts/payment.blade.php |
| Certificat de scolarité | CertificateService | certificates/enrollment.blade.php |
| Liste de classe | ReportService | reports/class-list.blade.php |
| Tableau d'honneur | ReportService | reports/honor-roll.blade.php |

---

## 🔔 Alertes dashboard

- Élèves avec 3+ absences non justifiées dans le mois
- Élèves avec paiement en retard (trimestre dépassé non payé)
- Enseignants absents sans remplacement
- Classes sans emploi du temps configuré
- Bulletins non générés en fin de trimestre

---

## 🌍 Contexte ivoirien
```
Téléphones   : Format +225 (10 chiffres)
Niveaux      : CP1 → CP2 → CE1 → CE2 → CM1 → CM2
Notes        : Sur 20
Monnaie      : FCFA
Dates        : dd/mm/yyyy
Trimestres   : 1er, 2ème, 3ème

Appréciations automatiques :
  18-20 → Excellent
  16-17 → Très Bien
  14-15 → Bien
  12-13 → Assez Bien
  10-11 → Passable
  0-9   → Insuffisant
```

---

## ⚠️ Contraintes LWS mutualisé

- Pas de queue worker permanent → scheduler Laravel via cron LWS
- Pas de WebSockets
- Storage local uniquement (`storage/app/public`)
- Compiler Vite en local → pousser `public/build/` sur le serveur
- Utiliser `chunk()` sur les grandes collections Eloquent
- Paginer tous les exports lourds

---

## 📝 Conventions de code

- Commentaires en français
- Messages UI en français
- Validation toujours via Form Requests (jamais inline)
- Logique métier toujours dans les Services
- Autorisations toujours via Policies
- Eager loading systématique avec `with()`
- Pagination : 15 items par défaut
- Soft deletes sur students, enrollments, payments

---

## 🚀 Ordre de développement
```
Phase 1 — Fondations
  [x] Migrations (toutes les tables)
  [x] Models + relations Eloquent
  [ ] Seeders (niveaux, matières, rôles)
  [ ] Auth + rôles
  [ ] Layout principal
  [ ] SchoolSettings

Phase 2 — Élèves
  [ ] CRUD élèves (fiche complète)
  [ ] Parents/tuteurs
  [ ] Documents & urgences
  [ ] Inscriptions

Phase 3 — Classes & Personnel
  [ ] CRUD classes et niveaux
  [ ] CRUD enseignants
  [ ] Affectation matières

Phase 4 — Emplois du temps
  [ ] FullCalendar drag & drop
  [ ] Détection conflits
  [ ] Export PDF

Phase 5 — Notes & Bulletins
  [ ] Grade periods
  [ ] Saisie notes
  [ ] GradeCalculatorService
  [ ] Bulletins PDF

Phase 6 — Paiements
  [ ] Grille tarifaire
  [ ] Saisie paiements
  [ ] Reçus PDF
  [ ] Suivi impayés

Phase 7 — Finitions
  [ ] Dashboard + alertes
  [ ] Absences
  [ ] Certificats & documents
  [ ] Exports Excel
  [ ] Backup config