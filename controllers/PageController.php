<?php
//  Incluimos los modelos que manejan los datos
include_once "app/models/UserModel.php";
include_once "app/models/ProductModel.php";
include_once "app/models/ScheduleModel.php";
include_once "app/models/ServiceModel.php";
include_once "app/models/NewsModel.php";
include_once "app/models/ContactModel.php";
include_once "app/models/FaqModel.php";
include_once "app/models/QuestionModel.php";
include_once "app/models/ReportModel.php";
include_once "app/models/BackupModel.php";
//Heredamos nuestro BaseController para función checkAuth() y validar roles
class PageController extends BaseController {

    private $conexion;
    private $userModel;
    private $productModel; 
    private $scheduleModel;
    private $serviceModel;
    private $newsModel;
    private $contactModel;
    private $faqModel;
    private $questionModel;
    private $reportModel;
    private $backupModel;


    // constructor para inicializar la conexion y los modelos
    /**
     * Constructor del controlador.
     * Inicializa todos los modelos necesarios.
     *
     * @param object $conexion Objeto de conexión a la base de datos.
     */
    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->userModel = new UserModel($conexion);
        $this->productModel = new ProductModel($conexion); 
        $this->scheduleModel = new ScheduleModel($conexion);
        $this->serviceModel = new ServiceModel($conexion);
        $this->newsModel = new NewsModel($conexion);
        $this->contactModel = new ContactModel($conexion);
        $this->faqModel = new FaqModel($conexion);
        $this->questionModel = new QuestionModel($conexion);
        $this->reportModel = new ReportModel($conexion);

        // Instancia del modelo de Respaldo
        // Pasa la config de la BD, no la conexión en sí
        global $server, $user, $password, $db;
        $this->backupModel = new BackupModel([
            'server' => $server,
            'user' => $user,
            'password' => $password,
            'db' => $db
        ]);


    }

    //FUNCION PARA CARGAR VISTAS CON DATOS 
    /**
     * Carga una vista y pasa los datos a la misma.
     * "extract($data)" toma un array asociativo y convierte sus claves
     * en variables. Ejemplo: $data['productos'] se convierte en $productos.
     * Esto hace que las variables estén disponibles para la vista.
     *
     * @param string $view_path Ruta relativa de la vista dentro de app/views/.
     * @param array $data Array asociativo de datos para extraer en la vista.
     * @return void
     */
    private function loadView($view_path, $data = []) {
        if (!empty($data)) {
            extract($data);
        }
        
        
        $full_path = "app/views/" . $view_path; 
        if (file_exists($full_path)) {
            include_once $full_path;
        } else {
            //  mensaje de error si la vista no se encuentra
            echo "Error fatal: No se pudo cargar la vista en: $full_path";
        }
    }

    
    // FUNCION PARA MOSTRAR LA VISTA DE LOGIN
    /**
     * Muestra la vista de inicio de sesión.
     *
     * @return void
     */
    public function showLogin() {

        $data = []; 

        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'credenciales_invalidas') $data['error_message'] = 'Correo o contraseña incorrectos.';
            if ($_GET['error'] == 'acceso_denegado') $data['error_message'] = 'Debes iniciar sesión para ver esa página.';
        }
        if (isset($_GET['registro']) && $_GET['registro'] == 'exitoso') {
            $data['success_message'] = '¡Registro exitoso! <br> Inicia sesión.';
        }
         if (isset($_GET['logout']) && $_GET['logout'] == 'exitoso') {
            $data['success_message'] = 'Has cerrado sesión correctamente.';
        }

        $this->loadView("auth/login.php", $data);
    }

    
    //FUNCION PARA MOSTRAR LA VISTA DE REGISTRO 
    /**
     * Muestra la vista de registro de usuarios.
     *
     * @return void
     */
    public function showRegister() {
        $data = []; 

        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'campos_vacios') $data['error_message'] = 'Todos los campos obligatorios deben llenarse.';
            if ($_GET['error'] == 'password_no_coincide') $data['error_message'] = 'Las contraseñas no coinciden.';
            if ($_GET['error'] == 'email_existe') $data['error_message'] = 'El correo electrónico ya está registrado.';
            if ($_GET['error'] == 'registro_fallido') $data['error_message'] = 'Error al registrar. Intente de nuevo.';
            if ($_GET['error'] == 'nombre_invalido') $data['error_message'] = 'Error: El nombre o apellidos contienen caracteres no válidos.';
            if ($_GET['error'] == 'telefono_invalido') $data['error_message'] = 'Error: El teléfono debe contener 10 dígitos numéricos.';
        }
        
        $this->loadView("auth/register.php", $data);
    }


    // FUNCION Muestra la página de inicio (main.php)
    /**
     * Muestra la página principal (Home).
     *
     * @return void
     */
    public function showMain() {
        $this->loadView("main.php");
    }

    // FUNCION PARA MOSTRAR EL PERFIL DEL CLIENTE
    /**
     * Muestra el perfil del cliente.
     * Requiere rol 'Cliente'.
     *
     * @return void
     */
    public function showProfile() {
        $this->checkAuth(['Cliente']); 

        $data = [
            'my_questions' => [],
            'error_db' => null
        ];
        
        // Mensaje de actualización de datos
        if(isset($_GET['status']) && $_GET['status'] == 'updated') {
            $data['success_message'] = '¡Tus datos han sido actualizados correctamente!';
        }
        
        if(isset($_GET['error']) && $_GET['error'] == 'nombre_invalido') {
            $data['error_message'] = 'Error: Tu nombre o apellidos contienen caracteres no válidos.';
        }
        if(isset($_GET['error']) && $_GET['error'] == 'telefono_invalido') {
            $data['error_message'] = 'Error: Tu teléfono debe contener 10 dígitos numéricos.';
        }

        // Mensajes de envío de preguntas
        if(isset($_GET['status']) && $_GET['status'] == 'pregunta_enviada') {
            $data['success_message'] = '¡Tu pregunta ha sido enviada! Te responderemos pronto.';
        }
        if(isset($_GET['error']) && $_GET['error'] == 'pregunta_vacia') {
            $data['error_message'] = 'Error: El campo de la pregunta no puede estar vacío.';
        }
        if(isset($_GET['error']) && $_GET['error'] == 'pregunta_fallida') {
            $data['error_message'] = 'Error al enviar tu pregunta. Intenta de nuevo.';
        }
        if(isset($_GET['status']) && $_GET['status'] == 'pregunta_eliminada') {
            $data['success_message'] = 'Tu pregunta ha sido eliminada correctamente.';
        }
        if(isset($_GET['error']) && $_GET['error'] == 'pregunta_no_eliminada') {
            $data['error_message'] = 'Error: No se pudo eliminar la pregunta.';
        }

        try {
            $data['my_questions'] = $this->questionModel->getQuestionsByClientId($_SESSION['usuario_id']);
        } catch (Exception $e) {
            $data['error_db'] = "Error al cargar tus preguntas: " . $e->getMessage();
        }
        
        $this->loadView("customer_profile.php", $data);
    }

    
    
    // --------------------------- FUNCIONES PARA ADMIN/TRABAJADOR -----------------------------

    // Muestra el Dashboard de Admin/Trabajador
    /**
     * Muestra el panel de control (Dashboard) para administradores y trabajadores.
     *
     * @return void
     */
    public function showDashboard() {
        $this->checkAuth(['Administrador', 'Trabajador']);
        
        $data = []; 
        $data['usuario_nombre'] = $_SESSION['usuario_nombre'];
        $data['usuario_rol'] = $_SESSION['usuario_rol'];
        
        // --- Inicio de carga de estadísticas ---
        $data['stats'] = [
            'pending_questions' => 0,
            'new_clients' => 0,
            'monthly_schedules' => 0
        ];

        try {
            // Llama a los modelos para obtener los conteos
            $data['stats']['pending_questions'] = $this->questionModel->getPendingQuestionsCount();
            $data['stats']['new_clients'] = $this->userModel->getNewClientsCount();
            $data['stats']['monthly_schedules'] = $this->reportModel->getCurrentMonthScheduleCount();
        
        } catch (Exception $e) {
            $data['error_db'] = "Error al cargar estadísticas: " . $e->getMessage();
        }
        // -----
        
        if(isset($_GET['error']) && $_GET['error'] == 'acceso_denegado') {
            $data['error_message'] = 'No tienes permiso para acceder a esa sección.';
        }
        if(isset($_GET['status']) && $_GET['status'] == 'updated') {
            $data['success_message'] = '¡Tus datos han sido actualizados correctamente!';
        }
        if(isset($_GET['error']) && $_GET['error'] == 'nombre_invalido') {
            $data['error_message'] = 'Error: Tu nombre o apellidos contienen caracteres no válidos.';
        }
        if(isset($_GET['error']) && $_GET['error'] == 'telefono_invalido') {
            $data['error_message'] = 'Error: Tu teléfono debe contener 10 dígitos numéricos.';
        }

        $this->loadView("admin/dashboard.php", $data);
    }


    // FUNCION PARA MOSTRAR PAGINA DE GESTIÓN DE USUARIOS
    /**
     * Muestra la página de gestión de usuarios.
     * Requiere rol 'Administrador'.
     *
     * @return void
     */
    public function showManageUsers() {
        $this->checkAuth(['Administrador']);

        $data = [ 
            'trabajadores' => [],
            'clientes' => [],
            'error_db' => null
        ];

        try {
            $data['trabajadores'] = $this->userModel->getUsers('trabajador');
            $data['clientes'] = $this->userModel->getUsers('cliente');
        } catch (Exception $e) {
            $data['error_db'] = "Error al consultar las tablas: " . $e->getMessage();
        }
        
        //  mensajes de error/éxito
        if(isset($_GET['error'])) {
            if($_GET['error'] == 'admin_not_deletable') $data['error_message'] = '<strong>Error:</strong> No se puede eliminar a un usuario Administrador.';
            if($_GET['error'] == 'delete_failed') $data['error_message'] = '<strong>Error:</strong> No se pudo eliminar al usuario.';
            if($_GET['error'] == 'creation_failed') $data['error_message'] = '<strong>Error:</strong> No se pudo crear el usuario.';
            if($_GET['error'] == 'campos_vacios') $data['error_message'] = '<strong>Error:</strong> Faltaron campos obligatorios al crear el usuario.';
            if($_GET['error'] == 'email_existe') $data['error_message'] = '<strong>Error:</strong> El correo electrónico ya está registrado.';
            if($_GET['error'] == 'update_failed') $data['error_message'] = '<strong>Error:</strong> No se pudo actualizar el usuario.';
            if($_GET['error'] == 'password_no_coincide') $data['error_message'] = '<strong>Error:</strong> Las contraseñas no coincidieron al crear el usuario.';
            if ($_GET['error'] == 'nombre_invalido') $data['error_message'] = '<strong>Error:</strong> El nombre o apellidos contienen caracteres no válidos.';
            if ($_GET['error'] == 'telefono_invalido') $data['error_message'] = '<strong>Error:</strong> El teléfono debe contener 10 dígitos numéricos.';
        }
        if(isset($_GET['status'])) {
            if($_GET['status'] == 'deleted') $data['success_message'] = 'Usuario eliminado correctamente.';
            if($_GET['status'] == 'created') $data['success_message'] = 'Usuario creado exitosamente.';
            if($_GET['status'] == 'updated') $data['success_message'] = 'Datos del usuario actualizados correctamente.';
        }

        $data['usuario_nombre'] = $_SESSION['usuario_nombre'];
        $data['usuario_rol'] = $_SESSION['usuario_rol'];
        
        $this->loadView("admin/manage_users.php", $data);
    }

    
    

    // FUNCIÓN para que editen SU PROPIO perfil
    /**
     * Muestra el formulario para editar el perfil del usuario actual.
     *
     * @return void
     */
    public function showEditForm() {
        $this->checkAuth(); // Solo verifica que esté logueado

        $data = []; 

        $user_id_to_edit = $_SESSION['usuario_id'];
        $user_type_to_edit = strtolower($_SESSION['usuario_rol']);
        
        $user = $this->userModel->getUserData($user_id_to_edit, $user_type_to_edit);
        
        if (!$user) {
            header("Location: " . BASE_URL . "index.php?accion=dashboard&error=user_not_found");
            exit;
        }

        $data['user'] = $user;
        $data['type'] = $user_type_to_edit;
        $data['usuario_nombre'] = $_SESSION['usuario_nombre'];
        $data['usuario_rol'] = $_SESSION['usuario_rol'];
        
        $this->loadView("admin/edit_user.php", $data);
    }

    
    

    // --------------------------- FUNCIONES PARA PRODUCTOS -----------------------------
    
    // FUNCION PARA MOSTRAR LA VISTA PUBLICA DE PRODUCTOS
    /**
     * Muestra la página pública de productos.
     *
     * @return void
     */
    public function showProducts() {

        $data = [ 
            'products' => [],
            'error_db' => null
        ];
        try {
            $data['products'] = $this->productModel->getActiveProducts();
        } catch (Exception $e) {
            $data['error_db'] = "Error al cargar los productos.";
        }
        $this->loadView("products.php", $data);
    }

    
    // FUNCION PARA MOSTRAR LA VISTA DE GESTION DE PRODUCTOS ADMIN
    /**
     * Muestra la página de gestión de productos.
     * Requiere rol 'Administrador' o 'Trabajador'.
     *
     * @return void
     */
    public function showManageProducts() {

        $this->checkAuth(['Administrador', 'Trabajador']);

        $data = [ 
            'products' => [],
            'error_db' => null
        ];

        try {
            $data['products'] = $this->productModel->getAllProductsAdmin();
        } catch (Exception $e) {
            $data['error_db'] = "Error al consultar la tabla de productos: " . $e->getMessage();
        }
        
        if(isset($_GET['status'])) {
            if($_GET['status'] == 'created') $data['success_message'] = '¡Producto creado exitosamente!';
            if($_GET['status'] == 'updated') $data['success_message'] = '¡Producto actualizado exitosamente!';
            if($_GET['status'] == 'deleted') $data['success_message'] = '¡Producto eliminado exitosamente!';
        }
        if(isset($_GET['error'])) {
            $data['error_message'] = 'Error al procesar la solicitud.';
        }

        $data['usuario_nombre'] = $_SESSION['usuario_nombre'];
        $data['usuario_rol'] = $_SESSION['usuario_rol'];
        
        $this->loadView("admin/manage_products.php", $data);
    }

    
    // --------------------------- FUNCIONES PARA HORARIOS -----------------------------

    // FUNCION PARA MOSTRAR LA VISTA PUBLICA DE HORARIOS
    /**
     * Muestra la página pública de horarios.
     *
     * @return void
     */
    public function showSchedules() {

        $data = [ 
            'schedules' => [],
            'error_db' => null
        ];
        try {
            $data['schedules'] = $this->scheduleModel->getActiveSchedulesPublic();
        } catch (Exception $e) {
            $data['error_db'] = "Error al cargar los horarios.";
        }
        $this->loadView("schedule.php", $data);
    }

    
    // FUNCION PARA MOSTRAR LA VISTA DE GESTIÓN DE HORARIOS ADMIN
    /**
     * Muestra la página de gestión de horarios.
     * Requiere rol 'Administrador' o 'Trabajador'.
     *
     * @return void
     */
    public function showManageSchedules() {

        $this->checkAuth(['Administrador', 'Trabajador']);

        $data = [ 
            'schedules' => [],
            'products' => [],
            'error_db' => null
        ];

        try {
            $data['schedules'] = $this->scheduleModel->getAllSchedulesAdmin();
            $data['products'] = $this->productModel->getAllProductsAdmin(); 
        } catch (Exception $e) {
            $data['error_db'] = "Error al consultar los datos: " . $e->getMessage();
        }
        
        if(isset($_GET['status'])) {
            if($_GET['status'] == 'created') $data['success_message'] = '¡Horario creado exitosamente!';
            if($_GET['status'] == 'updated') $data['success_message'] = '¡Horario actualizado exitosamente!';
            if($_GET['status'] == 'deleted') $data['success_message'] = '¡Horario eliminado exitosamente!';
        }
        if(isset($_GET['error'])) {
            $data['error_message'] = 'Error al procesar la solicitud.';
        }

        $data['usuario_nombre'] = $_SESSION['usuario_nombre'];
        $data['usuario_rol'] = $_SESSION['usuario_rol'];
        
        $this->loadView("admin/manage_schedules.php", $data);
    }
    
    // --------------------------- FUNCIONES PARA SERVICIOS -----------------------------

    
    //FUNCIÓN DE VISTA PUBLICA PARA SERVICIOS 
    /**
     * Muestra la página pública de servicios.
     *
     * @return void
     */
    public function showServices() {
        $data = [ 
            'services' => [],
            'error_db' => null
        ];
        try {
            // Llama al método público del nuevo modelo
            $data['services'] = $this->serviceModel->getActiveServicesPublic();
        } catch (Exception $e) {
            $data['error_db'] = "Error al cargar los servicios.";
        }
        $this->loadView("services.php", $data);
    }

    
    //FUNCIÓN DE VISTA GESTIÓN DE SERVICIOS DE ADMIN VALIDA SI ES ADMIN O TRABAJADOR
    /**
     * Muestra la página de gestión de servicios.
     * Requiere rol 'Administrador' o 'Trabajador'.
     *
     * @return void
     */
    public function showManageServices() {
        $this->checkAuth(['Administrador', 'Trabajador']);

        $data = [ 
            'services' => [],
            'error_db' => null
        ];

        try {
            // Obtiene todos los servicios para el admin
            $data['services'] = $this->serviceModel->getAllServicesAdmin();
        } catch (Exception $e) {
            $data['error_db'] = "Error al consultar la tabla de servicios: " . $e->getMessage();
        }
        
        // Lógica de mensajes de error/éxito para el CRUD
        if(isset($_GET['status'])) {
            if($_GET['status'] == 'created') $data['success_message'] = '¡Servicio creado exitosamente!';
            if($_GET['status'] == 'updated') $data['success_message'] = '¡Servicio actualizado exitosamente!';
            if($_GET['status'] == 'deleted') $data['success_message'] = '¡Servicio eliminado exitosamente!';
        }
        if(isset($_GET['error'])) {
            $data['error_message'] = 'Error al procesar la solicitud.';
        }

        // Pasa los datos de sesión a la vista
        $data['usuario_nombre'] = $_SESSION['usuario_nombre'];
        $data['usuario_rol'] = $_SESSION['usuario_rol'];
        
        $this->loadView("admin/manage_services.php", $data);
    }

// --------------------------- FUNCIONES PARA AVISOS (NEWS) -----------------------------


    //FUNCIÓN PARA MOSTRAR VISTA PUBLICA DE AVISOS
    //EL ARCHIVO SE LLAMA news.php pero la accion es anuncios para coincidir con el header
    /**
     * Muestra la página pública de avisos (noticias).
     *
     * @return void
     */
    public function showNews() {
        $data = [ 
            'news' => [], // El array se llamará news
            'error_db' => null
        ];
        try {
            $data['news'] = $this->newsModel->getPublicNews();
        } catch (Exception $e) {
            $data['error_db'] = "Error al cargar los avisos.";
        }
        $this->loadView("news.php", $data);
    }

    
    //FUNCIÓN PARA MOSTRAR GESTIÓN DE AVISOS PARA EL ADMIN
    /**
     * Muestra la página de gestión de avisos.
     * Requiere rol 'Administrador'.
     *
     * @return void
     */
    public function showManageNews() {
        // Solo los administradores pueden gestionar avisos
        $this->checkAuth(['Administrador']);

        $data = [ 
            'news' => [], // El array se llama news
            'error_db' => null
        ];

        try {
            $data['news'] = $this->newsModel->getAllNewsAdmin();
        } catch (Exception $e) {
            $data['error_db'] = "Error al consultar la tabla de avisos: " . $e->getMessage();
        }
        
        // Lógica de mensajes de error/éxito para el CRUD
        if(isset($_GET['status'])) {
            if($_GET['status'] == 'created') $data['success_message'] = '¡Aviso publicado exitosamente!';
            if($_GET['status'] == 'updated') $data['success_message'] = '¡Aviso actualizado exitosamente!';
            if($_GET['status'] == 'deleted') $data['success_message'] = '¡Aviso eliminado exitosamente!';
        }
        if(isset($_GET['error'])) {
            $data['error_message'] = 'Error al procesar la solicitud.';
        }

        //  Pasa los datos de sesión a la vista
        $data['usuario_nombre'] = $_SESSION['usuario_nombre'];
        $data['usuario_rol'] = $_SESSION['usuario_rol'];
        
        $this->loadView("admin/manage_news.php", $data);
    }



    // ---------------------------  FUNCIONES PARA CONTACTO -----------------------------

    
    //FUNCION DE VISTA PARA LA PARTE "NOSOTROS" about.php
    /**
     * Muestra la página "Acerca de".
     *
     * @return void
     */
    public function showAbout() {
        $this->loadView("about.php");
    }
    
    //FUNCIÓN PARA MOSTRAR VISTA PUBLICA DE CONTACTO 
    /**
     * Muestra la página pública de contacto.
     *
     * @return void
     */
    public function showContactPage() {
        $data = [ 
            'contact' => null, 
            'error_db' => null
        ];
        try {
            $data['contact'] = $this->contactModel->getContactInfo();
        } catch (Exception $e) {
            $data['error_db'] = "Error al cargar la información de contacto.";
        }

        $this->loadView("contact.php", $data);
    }

    
    // FUNCIÓN PARA MOSTRAR LA VISTA DE GESTIÓN DE CONTACTO PARA EL ADMIN
    /**
     * Muestra la página de gestión de contacto.
     * Requiere rol 'Administrador'.
     *
     * @return void
     */
    public function showManageContactPage() {
        $this->checkAuth(['Administrador']);

        $data = [ 
            'contact' => null,
            'error_db' => null
        ];

        try {
            $data['contact'] = $this->contactModel->getContactInfo();
            
            // Si no hay fila en la BD se crea un array vacío para evitar errores en el formulario
            if (!$data['contact']) {
                $data['contact'] = [
                    'telefono' => '', 
                    'direccion' => '', 
                    'correo_contacto' => '', 
                    'url_facebook' => ''
                ];
                $data['error_db'] = "No se encontró información de contacto. Por favor, guarde la información para crear el registro.";
            }

        } catch (Exception $e) {
            $data['error_db'] = "Error al cargar la información: " . $e->getMessage();
        }
        
        // Lógica de mensajes de error/éxito
        if(isset($_GET['status']) && $_GET['status'] == 'updated') {
            $data['success_message'] = '¡Información de contacto actualizada exitosamente!';
        }
        if(isset($_GET['error']) && $_GET['error'] == 'update_failed') {
            $data['error_message'] = 'Error al actualizar la información.';
        }

        // Pasa los datos de sesión a la vista
        $data['usuario_nombre'] = $_SESSION['usuario_nombre'];
        $data['usuario_rol'] = $_SESSION['usuario_rol'];
        
        $this->loadView("admin/manage_contact.php", $data);
    }


// --------------------------- FUNCIONES PARA FAQ -----------------------------

    // FUNCIÓN PARA VISTA PÚBLICA DE FAQ

    /**
     * Muestra la página pública de Preguntas Frecuentes (FAQ).
     *
     * @return void
     */
    public function showFaqPage() {
        $data = [ 
            'faqs' => [], // Para las preguntas del admin
            'client_questions' => [], // Para las preguntas de clientes
            'error_db' => null
        ];

        try {
            $data['faqs'] = $this->faqModel->getPublicFaqs();
            // Llama a la función del modelo
            $data['client_questions'] = $this->questionModel->getPublicQuestionsAndAnswers(); 
        } catch (Exception $e) {
            $data['error_db'] = "Error al cargar el contenido: " . $e->getMessage();
        }

        // Añade la lógica de mensajes para el formulario de esta página
        if(isset($_GET['status']) && $_GET['status'] == 'pregunta_enviada') {
            $data['success_message'] = '¡Tu pregunta ha sido enviada! Te responderemos pronto...';
        }
        if(isset($_GET['error']) && $_GET['error'] == 'pregunta_vacia') {
            $data['error_message'] = 'Error: El campo de la pregunta no puede estar vacío.';
        }

        $this->loadView("faq.php", $data);
    }

   
    //FUNCIÓN PARA VISTA DE GESTIÓN FAQ DEL ADMIN
    /**
     * Muestra la página de gestión de FAQ.
     * Requiere rol 'Administrador'.
     *
     * @return void
     */
    public function showManageFaqPage() {
        $this->checkAuth(['Administrador']);

        $data = [ 
            'faqs' => [],
            'error_db' => null
        ];

        try {
            $data['faqs'] = $this->faqModel->getAllFaqsAdmin();
        } catch (Exception $e) {
            $data['error_db'] = "Error al consultar la tabla de FAQ: " . $e->getMessage();
        }
        
        // Lógica de mensajes de error/éxito para el CRUD
        if(isset($_GET['status'])) {
            if($_GET['status'] == 'created') $data['success_message'] = '¡Pregunta añadida exitosamente!';
            if($_GET['status'] == 'updated') $data['success_message'] = '¡Pregunta actualizada exitosamente!';
            if($_GET['status'] == 'deleted') $data['success_message'] = '¡Pregunta eliminada exitosamente!';
            if($_GET['status'] == 'toggled') $data['success_message'] = 'Visibilidad de la pregunta actualizada.';
        }
        if(isset($_GET['error'])) {
            $data['error_message'] = 'Error al procesar la solicitud.';
        }

        $data['usuario_nombre'] = $_SESSION['usuario_nombre'];
        $data['usuario_rol'] = $_SESSION['usuario_rol'];
        
        $this->loadView("admin/manage_faq.php", $data);
    }

    // --------------------------- FUNCIONES PARA PREGUNTAS DE CLIENTES -----------------------------

    
    // FUNCIÓN PARA MOSTRAR VISTA DE GESTIÓN DE PREGUNTAS ADMIN/TRABAJADOR
    
    /**
     * Muestra la página de gestión de preguntas de clientes.
     * Requiere rol 'Administrador' o 'Trabajador'.
     *
     * @return void
     */
    public function showManageQuestionsPage() {
        $this->checkAuth(['Administrador', 'Trabajador']);

        $data = [ 
            'questions' => [],
            'error_db' => null
        ];

        try {
            $data['questions'] = $this->questionModel->getAllQuestionsAdmin();
        } catch (Exception $e) {
            $data['error_db'] = "Error al consultar la tabla de preguntas: " . $e->getMessage();
        }
        
        // lógica para mensaje de error o exito
        if(isset($_GET['status'])) {
            if($_GET['status'] == 'answered') $data['success_message'] = '¡Pregunta respondida exitosamente!';
            if($_GET['status'] == 'deleted') $data['success_message'] = '¡Pregunta eliminada exitosamente!';
            // COMENTARIO NUEVO: Mensaje para la nueva función de editar
            if($_GET['status'] == 'updated') $data['success_message'] = '¡Respuesta actualizada exitosamente!';
        }
        if(isset($_GET['error'])) {
            if($_GET['error'] == 'answer_failed') $data['error_message'] = 'Error al guardar la respuesta.';
            if($_GET['error'] == 'delete_failed') $data['error_message'] = 'Error al eliminar la pregunta.';
            if($_GET['error'] == 'respuesta_vacia') $data['error_message'] = 'Error: El campo de respuesta no puede estar vacío.';
            if($_GET['error'] == 'update_failed') $data['error_message'] = 'Error al actualizar la respuesta.';
        }

        $data['usuario_nombre'] = $_SESSION['usuario_nombre'];
        $data['usuario_rol'] = $_SESSION['usuario_rol'];
        
        $this->loadView("admin/manage_questions.php", $data);
    }


    // --------------------------- FUNCIONES PARA REPORTES -----------------------------

    // FUNCION PARA MOSTRAR VISTA DE REPORTES
    /**
     * Muestra la página de reportes.
     * Requiere rol 'Administrador'.
     *
     * @return void
     */
    public function showReportsPage() {
        $this->checkAuth(['Administrador']);

        $currentYear = $_GET['year'] ?? date('Y');
        $currentMonth = $_GET['month'] ?? date('m');
        $weekStartDate = $_GET['week_start'] ?? null;
        $weekEndDate = $_GET['week_end'] ?? null;

        $data = [
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth,
            'weekStartDate' => $weekStartDate,
            'weekEndDate' => $weekEndDate,
            'monthlyReport' => [],
            'weeklyReport' => null, 
            'topProducts' => [],
            'currentMonthScheduleCount' => 0,
            'allClients' => [], 
            'error_db' => null
        ];

        try {
            // Se obtienen los datos de los clientes
            $data['allClients'] = $this->reportModel->getAllClients();
            $data['currentMonthScheduleCount'] = $this->reportModel->getCurrentMonthScheduleCount();
            $data['monthlyReport'] = $this->reportModel->getMonthlyScheduleDetails($currentYear, $currentMonth);
            
            $topProducts = $this->reportModel->getMostProgrammedProducts($currentYear, $currentMonth);
            
            // Se calcula el porcentaje
            $totalHorariosSum = array_sum(array_column($topProducts, 'total_horarios'));
            foreach ($topProducts as $key => $product) {
                $percentage = ($totalHorariosSum > 0) ? ($product['total_horarios'] / $totalHorariosSum) * 100 : 0;
                $topProducts[$key]['porcentaje'] = number_format($percentage, 2) . '%';
            }
            $data['topProducts'] = $topProducts;
            // -------

            if ($weekStartDate && $weekEndDate) {
                $data['weeklyReport'] = $this->reportModel->getWeeklyScheduleDetails($weekStartDate, $weekEndDate);
            }

        } catch (Exception $e) {
            $data['error_db'] = "Error al generar el reporte: " . $e->getMessage();
        }

        $data['usuario_nombre'] = $_SESSION['usuario_nombre'];
        $data['usuario_rol'] = $_SESSION['usuario_rol'];
        
        $this->loadView("admin/reports.php", $data);
    }


    // --------------------------- FUNCIONES PARA RESPALDOS -----------------------------

    //FUNCIÓN PARA MOSTRAR GESTIÓN DE RESPALDOS
    /**
     * Muestra la página de gestión de respaldos.
     * Requiere rol 'Administrador'.
     *
     * @return void
     */
    public function showManageBackupsPage() {
        $this->checkAuth(['Administrador']);

        $data = [
            'backups' => [],
            'error_db' => null
        ];

        try {
            // Se llama al modelo para listar los archivos .sql
            $data['backups'] = $this->backupModel->listBackups();
        } catch (Exception $e) {
            $data['error_db'] = "Error al leer el directorio de respaldos: " . $e->getMessage();
        }
        
        // Lógica de mensajes de éxito/error
        if(isset($_GET['status'])) {
            if($_GET['status'] == 'created') $data['success_message'] = '¡Respaldo creado exitosamente! (' . htmlspecialchars($_GET['file'] ?? '') . ')';
            if($_GET['status'] == 'deleted') $data['success_message'] = 'Respaldo eliminado correctamente.';
            if($_GET['status'] == 'restored') $data['success_message'] = '¡Restauración de la base de datos completada exitosamente!';
        }
        if(isset($_GET['error'])) {
            if($_GET['error'] == 'failed') $data['error_message'] = 'Error: No se pudo crear el respaldo.';
            if($_GET['error'] == 'file_not_found') $data['error_message'] = 'Error: No se encontró el archivo de respaldo.';
            if($_GET['error'] == 'delete_failed') $data['error_message'] = 'Error: No se pudo eliminar el respaldo.';
            //  Mensajes de error para restaurar/importar
            if($_GET['error'] == 'restore_failed') $data['error_message'] = 'Error: Falló la restauración de la base de datos.';
            if($_GET['error'] == 'upload_error') $data['error_message'] = 'Error: Hubo un problema al subir el archivo.';
            if($_GET['error'] == 'invalid_type') $data['error_message'] = 'Error: El archivo subido no es un .sql válido.';
            if($_GET['error'] == 'upload_failed') $data['error_message'] = 'Error: No se seleccionó ningún archivo para subir.';
        }

        $data['usuario_nombre'] = $_SESSION['usuario_nombre'];
        $data['usuario_rol'] = $_SESSION['usuario_rol'];
        
        $this->loadView("admin/manage_backups.php", $data);
    }

}
?>