<?php

// VERSION | 1.0.0 | 16 noviembre 2025
// Archivo principal de la aplicación Estancia Molino Lolis

// Carga de todas las bibliotecas de Composer Uso: dompdf
require __DIR__ . '/vendor/autoload.php';
// Iniciar la sesión al principio de todo
session_start();

// Incluir configuración y la conexión
include_once "config/config.php";
include_once "config/db_connection.php";

// CONTROLLERS ZONE
// BaseController PRIMERO
include_once "app/controllers/BaseController.php";
include_once "app/controllers/UserController.php";
include_once "app/controllers/PageController.php";
include_once "app/controllers/ProductController.php"; 
include_once "app/controllers/ScheduleController.php"; 
include_once "app/controllers/ServiceController.php"; 
include_once "app/controllers/NewsController.php";
include_once "app/controllers/ContactController.php";
include_once "app/controllers/FaqController.php";
include_once "app/controllers/QuestionController.php";

include_once "app/models/ReportModel.php"; // importante incluir modelo se aquí para el controlador de reporte
include_once "app/controllers/ReportController.php";

//CONTROLADOR Y MODELO PARA RESPALDOS
include_once "app/models/BackupModel.php";
include_once "app/controllers/BackupController.php";

// Objeto de controladores
$userController = new UserController($conexion);
$pageController = new PageController($conexion);
$productController = new ProductController($conexion); 
$scheduleController = new ScheduleController($conexion); 
$serviceController = new ServiceController($conexion); 
$newsController = new NewsController($conexion);
$contactController = new ContactController($conexion);
$faqController = new FaqController($conexion);
$questionController = new QuestionController($conexion);
$reportController = new ReportController($conexion);
$backupController = new BackupController($conexion);

$accion = $_GET['accion'] ?? 'main'; // main es la acción por defecto (página de inicio)

switch ($accion) {
    //================================================================
    // --- Rutas de Páginas ---
    case 'main':
        $pageController->showMain();
        break;
    case 'dashboard':
        $pageController->showDashboard();
        break;
    //================================================================
    // --- Rutas de Productos ---
    case 'products': // Vista pública
        $pageController->showProducts();
        break;
    case 'manage_products': // Vista de admin
        $pageController->showManageProducts();
        break;
    case 'create_product': // Acción de crear
        $productController->createProduct();
        break;
    case 'update_product': // Acción de actualizar
        $productController->updateProduct();
        break;
    case 'toggle_product_status': // Activar o desactivar visibilidad de un producto
        $productController->toggleProductStatus();
        break;
    case 'delete_product': // Acción de borrar
        $productController->deleteProduct();
        break;
    //================================================================
    // --- Rutas de Usuarios ---
    case 'login': // Pagina de login
        $pageController->showLogin(); 
        break;
    case 'do_login': // Accion de login
        $userController->doLogin(); 
        break;
    case 'register':
        $pageController->showRegister();
        break;
    case 'do_register':
        $userController->doRegister();
        break;
    case 'logout':
        $userController->logout();
        break;
    case 'profile':
        $pageController->showProfile();
        break;
    case 'manage_users':
        $pageController->showManageUsers();
        break;
    //================================================================
    // rutas para el formulario de admin
    
    case 'adminCreateUser':
        $userController->adminCreateUser();
        break;

    case 'showEditForm':
        $pageController->showEditForm();
        break;
    case 'updateUser':
        $userController->updateUser();
        break;
    case 'deleteUser':
        $userController->deleteUser();
        break;
    //================================================================
    // --- Rutas de Horarios ---
    case 'schedule': // Vista pública
        $pageController->showSchedules();
        break;
    case 'manage_schedules': // Vista de admin
        $pageController->showManageSchedules();
        break;
    case 'create_schedule': // Acción de crear
        $scheduleController->createSchedule();
        break;
    case 'update_schedule': // Acción de actualizar
        $scheduleController->updateSchedule();
        break;
    case 'delete_schedule': // Acción de borrar
        $scheduleController->deleteSchedule();
        break;
    //================================================================
    // ---  Rutas de Servicios ---
    case 'services': // Vista pública
        $pageController->showServices();
        break;
    case 'manage_services': // Vista de admin
        $pageController->showManageServices();
        break;
    case 'create_service': // Acción de crear
        $serviceController->createService();
        break;
    case 'update_service': // Acción de actualizar
        $serviceController->updateService();
        break;
    case 'delete_service': // Acción de borrar
        $serviceController->deleteService();
        break;
    //================================================================
    // ---  Rutas de Avisos (News) ---
    case 'anuncios': // Vista pública 
        $pageController->showNews();
        break;
    case 'manage_news': // Vista de admin
        $pageController->showManageNews();
        break;
    case 'create_news': // Acción de crear
        $newsController->createNews();
        break;
    case 'update_news': // Acción de actualizar
        $newsController->updateNews();
        break;
    case 'delete_news': // Acción de borrar
        $newsController->deleteNews();
        break;
    //================================================================
    // --- Rutas de Contacto 

    case 'about': // ruta para "Sobre nosotros"
        $pageController->showAbout();
        break;

    case 'contact': // Vista pública 
        $pageController->showContactPage();
        break;
    case 'manage_contact': // Vista de admin
        $pageController->showManageContactPage();
        break;
    case 'update_contact': // Acción de actualizar
        $contactController->updateContact();
        break;
    //================================================================
    // --- Rutas de FAQ (Dudas)
    case 'dudas': // Vista pública 
        $pageController->showFaqPage();
        break;
    case 'manage_faq': // Vista de admin
        $pageController->showManageFaqPage();
        break;
    case 'create_faq': // Acción de crear
        $faqController->createFaq();
        break;
    case 'update_faq': // Acción de actualizar
        $faqController->updateFaq();
        break;
    case 'delete_faq': // Acción de borrar
        $faqController->deleteFaq();
        break;
    case 'toggle_faq_visibility': // Acción de cambiar visibilidad
        $faqController->toggleFaqVisibility();
        break;
    //================================================================
    // --- Rutas de Preguntas de Clientes
    case 'manage_questions': // Vista de admin
        $pageController->showManageQuestionsPage();
        break;
    case 'submit_question': // Acción del Cliente
        $questionController->submitQuestion();
        break;
    case 'answer_question': // Acción del Admin/Trabajador
        $questionController->answerQuestion();
        break;
    case 'update_answer':  // Accion de editar respuesta a pregunta
        $questionController->updateAnswer();
        break;
    case 'delete_question': // Acción del Admin/Trabajador
        $questionController->deleteQuestion();
        break;
    case 'client_delete_question': //Para cuando el cliente quiere borrar su pregunta
        $questionController->clientDeleteQuestion();
        break;
    //================================================================
        // ---  Rutas para Reportes 
    case 'manage_reports':
        $pageController->showReportsPage();
        break;
    case 'download_monthly_report':
        $reportController->downloadMonthlyReport();
        break;
    case 'download_weekly_report':
        $reportController->downloadWeeklyReport();
        break;
    //================================================================
        // --- Rutas para Respaldos
    case 'manage_backups':
        $pageController->showManageBackupsPage();
        break;
    case 'create_backup':
        $backupController->createBackup();
        break;
    case 'download_backup':
        $backupController->downloadBackup();
        break;
    case 'delete_backup':
        $backupController->deleteBackup();
        break;
    //================================================================
    // Rutas para restaurar
    case 'restore_backup':
        $backupController->restoreBackup();
        break;
    case 'upload_backup':
        $backupController->uploadAndRestoreBackup();
        break;

    default:
        $pageController->showMain();
        break;
}
?>