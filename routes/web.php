<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EducationLevelController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\GradeLevelController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\WaliSantriController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\StudentPlacementController;
use App\Http\Controllers\RolloverController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Routes Web — SIMAD
|--------------------------------------------------------------------------
| Fase 1: Auth (login/logout)
| Fase 2: Dashboard dengan data real
| Fase 3: Master Data CRUD
| Fase 4: Akademik Engine (Kurikulum, Kenaikan Kelas)
| Fase 5: Presensi, Nilai, E-Raport
|--------------------------------------------------------------------------
*/

// ==============================================================
// GUEST ROUTES — Redirect ke dashboard jika sudah login
// ==============================================================
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

// ==============================================================
// AUTHENTICATED ROUTES
// ==============================================================
Route::middleware('auth')->group(function () {

    // ── Dashboard ──────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── FASE 3: Master Data CRUD ───────────────────────────────

    // 1. Unit Pendidikan
    Route::resource('education-levels', EducationLevelController::class);

    // 2. Tahun Ajaran + route tambahan untuk set active
    Route::resource('academic-years', AcademicYearController::class);
    Route::patch('academic-years/{academicYear}/set-active', [AcademicYearController::class, 'setActive'])
        ->name('academic-years.set-active')->middleware('can:edit-tahun');

    // 2.1 Kuartal / Term (Menu Terpisah)
    Route::get('academic-terms', [\App\Http\Controllers\AcademicTermController::class, 'index'])
        ->name('academic-terms.index');
    Route::patch('academic-terms/{academicTerm}/set-active', [\App\Http\Controllers\AcademicTermController::class, 'setActive'])
        ->name('academic-terms.set-active')->middleware('can:edit-kuartal');

    // 3. Mata Pelajaran
    Route::resource('subjects', SubjectController::class);

    // 4. Tingkat Kelas
    Route::resource('grade-levels', GradeLevelController::class);

    // 5. Kelas
    Route::resource('classrooms', ClassroomController::class);

    // 6. Data Guru + toggle active
    Route::resource('teachers', TeacherController::class);
    Route::patch('teachers/{teacher}/toggle-active', [TeacherController::class, 'toggleActive'])
        ->name('teachers.toggle-active')->middleware('can:edit-guru');

    // 7. Data Santri
    Route::resource('students', StudentController::class);

    // 8. Wali Santri
    Route::resource('wali-santri', WaliSantriController::class);

    // 9. Kurikulum
    Route::resource('curriculums', CurriculumController::class);
    Route::post('curriculums/duplicate', [CurriculumController::class, 'duplicate'])
        ->name('curriculums.duplicate');


    // ── FASE 4: Akademik Engine ────────────────────────────────

    // 10. Penempatan Santri ke Kelas
    Route::resource('student-placements', StudentPlacementController::class)
         ->only(['index', 'store', 'update', 'destroy']);
    Route::post('student-placements/bulk', [StudentPlacementController::class, 'bulkStore'])
         ->name('student-placements.bulk');

    // 11. Year-End Rollover Wizard
    Route::prefix('rollover')->name('rollover.')->group(function () {
        Route::get('/',        [RolloverController::class, 'index'])->name('index');
        Route::get('/step/1',  [RolloverController::class, 'showStep1'])->name('step1');
        Route::post('/step/1', [RolloverController::class, 'processStep1'])->name('step1.process');
        Route::get('/step/2',  [RolloverController::class, 'showStep2'])->name('step2');
        Route::post('/step/2', [RolloverController::class, 'processStep2'])->name('step2.process');
        Route::get('/step/3',  [RolloverController::class, 'showStep3'])->name('step3');
        Route::post('/step/3', [RolloverController::class, 'processStep3'])->name('step3.process');
    });

    // 12. Presensi
    Route::prefix('attendances')->name('attendances.')->group(function () {
        Route::get('/',      [AttendanceController::class, 'index'])->name('index');
        Route::post('/',     [AttendanceController::class, 'store'])->name('store');
        Route::get('/recap', [AttendanceController::class, 'recap'])->name('recap');
    });

    // 13. Input Nilai
    Route::prefix('grades')->name('grades.')->group(function () {
        Route::get('/',      [GradeController::class, 'index'])->name('index');
        Route::post('/',     [GradeController::class, 'store'])->name('store');
        Route::get('/recap', [GradeController::class, 'recap'])->name('recap');
    });

    // 14. E-Raport
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',                    [ReportController::class, 'index'])->name('index');
        Route::get('/{student}',           [ReportController::class, 'show'])->name('show');
        Route::get('/{student}/print',     [ReportController::class, 'printReport'])->name('print');
    });

    // 15. Nilai Kepribadian
    Route::prefix('personality')->name('personality.')->group(function () {
        Route::get('/',      [\App\Http\Controllers\PersonalityGradeController::class, 'index'])->name('index');
        Route::post('/',     [\App\Http\Controllers\PersonalityGradeController::class, 'store'])->name('store');
    });

    // ── FASE 6: Pengaturan (Super Admin Only) ──────────────────
    Route::prefix('settings')->group(function () {
        // Hak Akses (Permissions)
        Route::get('/permissions',    [\App\Http\Controllers\PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions',   [\App\Http\Controllers\PermissionController::class, 'store'])->name('permissions.store');
        Route::delete('/permissions/{permission}', [\App\Http\Controllers\PermissionController::class, 'destroy'])->name('permissions.destroy');
        
        // Manajemen Role
        Route::resource('roles', \App\Http\Controllers\RoleController::class);

        // Manajemen User
        Route::resource('users', \App\Http\Controllers\UserController::class);
        
        // Profil Induk
        Route::get('/school-profile', [\App\Http\Controllers\SystemSettingController::class, 'indexInstitution'])
            ->name('settings.school');
        
        // Profil Aplikasi
        Route::get('/app-profile',    [\App\Http\Controllers\SystemSettingController::class, 'indexApp'])
            ->name('settings.app');
        
        // Update Action (POST)
        Route::post('/update', [\App\Http\Controllers\SystemSettingController::class, 'update'])
            ->name('settings.update');
            
    });

});


// ==============================================================
// LOGOUT
// ==============================================================
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');