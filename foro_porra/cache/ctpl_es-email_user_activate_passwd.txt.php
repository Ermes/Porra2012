<?php if (!defined('IN_PHPBB')) exit; ?>Subject: Activación de nueva clave

Hola <?php echo (isset($this->_rootref['USERNAME'])) ? $this->_rootref['USERNAME'] : ''; ?>


Está recibiendo esta notificación porque usted (o alguien reemplazándole) solicitó una nueva clave 
para su cuenta en "<?php echo (isset($this->_rootref['SITENAME'])) ? $this->_rootref['SITENAME'] : ''; ?>". Si usted no lo solicitó por favor ignore esta notificación. 
Si persiste la solicitud contacte con La Administración del Sitio.

Para usar la nueva clave necesita activarla. Para ello visite el enlace siguiente.

<?php echo (isset($this->_rootref['U_ACTIVATE'])) ? $this->_rootref['U_ACTIVATE'] : ''; ?>


Si no hay inconvenientes podrá identificarse mediante la siguiente nueva clave:

Clave: <?php echo (isset($this->_rootref['PASSWORD'])) ? $this->_rootref['PASSWORD'] : ''; ?>


Por supuesto posteriormente puede cambiar esta clave para su cuenta mediante el Panel de Control de Usuario. 
Si tiene alguna dificultad contacte con La Administración del Sitio.

<?php echo (isset($this->_rootref['EMAIL_SIG'])) ? $this->_rootref['EMAIL_SIG'] : ''; ?>