<?php
/* Initializing WordPress features */
require_once('../../../wp-blog-header.php');

/* Initializing Smarty and Database Objects */
require_once '../../config/config.php';
require_once '../../config/smarty.php';
require_once '../../config/db.php';
require_once WORKSPACE_DIR . 'core/dao/factories/DAOFactory.php';

/* Show WordPress Header */
get_header();

// display sidebar
get_sidebar();

$taxonDAO = DAOFactory::getDAOFactory()->getTaxonDAO();

$taxonDAO->updateScientificNames();


// display footer
get_footer();


?>
