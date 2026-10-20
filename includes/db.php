<?php
/**
 * AL MAQRA (المقرأ) - Database Handler & Table Initializer
 */

require_once __DIR__ . '/../config/database.php';

$pdo = getDBConnection();
ensureDatabaseTablesExist($pdo);

/**
 * Ensures all required tables and default baseline records exist.
 */
function ensureDatabaseTablesExist(PDO $pdo): void {
    $driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

    if ($driver === 'sqlite') {
        // SQLite table creation
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS admins (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL UNIQUE,
                password_hash TEXT NOT NULL,
                role TEXT DEFAULT 'admin',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS courses (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                slug TEXT NOT NULL UNIQUE,
                category TEXT DEFAULT 'Quran',
                description TEXT,
                duration TEXT DEFAULT '3 - 6 Months',
                level TEXT DEFAULT 'Beginner to Advanced',
                is_active INTEGER DEFAULT 1,
                sort_order INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS teachers (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                full_name TEXT NOT NULL,
                gender TEXT DEFAULT 'Male',
                email TEXT,
                phone TEXT,
                qualification TEXT,
                specialization TEXT,
                languages TEXT DEFAULT 'Malayalam, English, Arabic',
                status TEXT DEFAULT 'active',
                bio TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS students (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                full_name TEXT NOT NULL,
                age INTEGER,
                gender TEXT DEFAULT 'Male',
                parent_name TEXT,
                email TEXT,
                phone TEXT NOT NULL,
                country TEXT DEFAULT 'India',
                course_id INTEGER,
                course_title TEXT,
                preferred_time TEXT,
                notes TEXT,
                status TEXT DEFAULT 'trial_requested',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS classes (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                course_id INTEGER,
                teacher_id INTEGER,
                schedule_days TEXT DEFAULT 'Mon, Wed, Fri',
                time_slot TEXT DEFAULT '07:00 PM - 07:45 PM',
                meeting_link TEXT,
                status TEXT DEFAULT 'active',
                max_students INTEGER DEFAULT 1,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );

            CREATE TABLE IF NOT EXISTS class_students (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                class_id INTEGER NOT NULL,
                student_id INTEGER NOT NULL,
                status TEXT DEFAULT 'active',
                enrolled_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                UNIQUE(class_id, student_id)
            );
        ");
    } else {
        // MySQL table creation
        $schemaFile = __DIR__ . '/../database/schema.sql';
        if (file_exists($schemaFile)) {
            $sql = file_get_contents($schemaFile);
            $pdo->exec($sql);
        }
    }

    // Seed default courses and admin if empty
    seedInitialDataIfEmpty($pdo);
}

/**
 * Seeds initial courses and default admin account if table is empty.
 */
function seedInitialDataIfEmpty(PDO $pdo): void {
    // Check if admins table has an account
    $stmt = $pdo->query("SELECT COUNT(*) FROM admins");
    $adminCount = (int)$stmt->fetchColumn();

    if ($adminCount === 0) {
        $adminName = (string)env('DEFAULT_ADMIN_NAME', 'Al Maqra Admin');
        $adminEmail = (string)env('DEFAULT_ADMIN_EMAIL', 'admin@almaqra.com');
        $adminPass = (string)env('DEFAULT_ADMIN_PASSWORD', 'admin123');
        $defaultPassword = password_hash($adminPass, PASSWORD_BCRYPT);
        $insertAdmin = $pdo->prepare("
            INSERT INTO admins (name, email, password_hash, role)
            VALUES (?, ?, ?, 'admin')
        ");
        $insertAdmin->execute([$adminName, $adminEmail, $defaultPassword]);
    }

    // Check if courses table is populated
    $stmtCourses = $pdo->query("SELECT COUNT(*) FROM courses");
    $coursesCount = (int)$stmtCourses->fetchColumn();

    if ($coursesCount === 0) {
        $initialCourses = [
            [
                'title' => 'Noorani Qaida Course (Beginners & Kids)',
                'slug' => 'noorani-qaida',
                'category' => 'Beginner',
                'description' => 'Master Arabic alphabets, correct pronunciation (Makharij), joint letters, and basic recitation rules for children and adult beginners.',
                'duration' => '2 - 3 Months',
                'level' => 'Beginner (Age 4+)',
                'sort_order' => 1
            ],
            [
                'title' => 'Online Quran Reading with Tajweed',
                'slug' => 'quran-reading-tajweed',
                'category' => 'Recitation',
                'description' => 'Recite the Holy Qur’an fluently and correctly with foundational Tajweed rules (Noon Sakinah, Meem Sakinah, Madd, Ghunnah).',
                'duration' => '4 - 6 Months',
                'level' => 'Elementary to Intermediate',
                'sort_order' => 2
            ],
            [
                'title' => 'Quran Tajweed Mastery & Makharij',
                'slug' => 'tajweed-mastery',
                'category' => 'Advanced Tajweed',
                'description' => 'Advanced theoretical and practical Tajweed rules, points of articulation (Makharij al-Huroof), and characteristics (Sifaat) for beautiful recitation.',
                'duration' => '3 - 5 Months',
                'level' => 'Intermediate to Advanced',
                'sort_order' => 3
            ],
            [
                'title' => 'Online Quran Memorization (Hifz Program)',
                'slug' => 'quran-hifz-program',
                'category' => 'Memorization',
                'description' => 'Structured, guided Qur’an memorization with daily revision (Sabaq, Sabqi, Manzil) supervised by experienced Huffaz.',
                'duration' => 'Flexible (1 - 3 Years)',
                'level' => 'All Levels (Committed)',
                'sort_order' => 4
            ],
            [
                'title' => 'Islamic Studies & Deeniyath for Kids & Adults',
                'slug' => 'islamic-studies-deeniyath',
                'category' => 'Islamic Studies',
                'description' => 'Daily essential Duas, Salah/Namaz training, Islamic manners (Adab), Seerah of Prophet Muhammad (PBUH), and fundamental Islamic morals.',
                'duration' => 'Ongoing',
                'level' => 'Kids & Youth',
                'sort_order' => 5
            ]
        ];

        $insCourse = $pdo->prepare("
            INSERT INTO courses (title, slug, category, description, duration, level, sort_order)
            VALUES (:title, :slug, :category, :description, :duration, :level, :sort_order)
        ");

        foreach ($initialCourses as $course) {
            $insCourse->execute($course);
        }
    }

    // Seed default sample teachers if empty
    $stmtTeachers = $pdo->query("SELECT COUNT(*) FROM teachers");
    $teacherCount = (int)$stmtTeachers->fetchColumn();

    if ($teacherCount === 0) {
        $initialTeachers = [
            [
                'full_name' => 'Ustadh Hafiz Muhammad Bilal',
                'gender' => 'Male',
                'email' => 'bilal@almaqra.com',
                'phone' => '+91 9497577616',
                'qualification' => 'Hafiz-ul-Quran & Qari (Ijazah in Hafs ‘an ‘Asim)',
                'specialization' => 'Hifz Program, Advanced Tajweed, Makharij',
                'languages' => 'Malayalam, English, Arabic',
                'status' => 'active',
                'bio' => 'Over 8 years experience teaching Tajweed and Hifz to international students across UAE, UK and India.'
            ],
            [
                'full_name' => 'Ustadha Fatima Al-Zahra',
                'gender' => 'Female',
                'email' => 'fatima@almaqra.com',
                'phone' => '+91 9497577616',
                'qualification' => 'Alimah & Sanad holder in Tajweed',
                'specialization' => 'Noorani Qaida for Kids, Nazira Quran for Female Students',
                'languages' => 'Malayalam, English, Urdu',
                'status' => 'active',
                'bio' => 'Specializes in patient, child-friendly foundational Arabic phonetics and gentle 1-on-1 recitation guidance.'
            ]
        ];

        $insTeacher = $pdo->prepare("
            INSERT INTO teachers (full_name, gender, email, phone, qualification, specialization, languages, status, bio)
            VALUES (:full_name, :gender, :email, :phone, :qualification, :specialization, :languages, :status, :bio)
        ");

        foreach ($initialTeachers as $teacher) {
            $insTeacher->execute($teacher);
        }
    }
}
