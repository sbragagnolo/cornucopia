<?php


/**
 * Common
 **/
Container::register("BasicObject", "BasicObject", "../common/BasicObject.php",Container::Common());


/**
 * Collections
 **/
Container::register("Collection", "Collection", "../collection/Collection.php",Container::Common());
Container::register("Dictionary", "Dictionary", "../collection/Dictionary.php",Container::Common());



/**
 * DB Access 
 **/

Container::register("MySqlConnection", "MySql", "../db/connection/MySql.php",Container::Common());
Container::register("Repository", "Repository", "../db/repository/Repository.php",Container::Singleton());
Container::register ("Connection", "MySql", "../db/connection/MySql.php", Container::Singleton());



/**
 * Paginators.
 **/

define("RECORDS_PER_PAGE", 25);

Container::register ("Paginator", "Paginator", "../paginator/Paginator.php", Container::Common(), array(function($paginator){ $paginator->setBatchSize(RECORDS_PER_PAGE); }));
Container::register ("PersistentPaginator", "PersistentPaginator", "../paginator/PersistentPaginator.php", Container::Common(), array(function($paginator){ $paginator->setBatchSize(RECORDS_PER_PAGE); }));




/**
 * Request 
 **/

Container::register("Request", "Request", "../request/Request.php",Container::Singleton());

/**
 * Session 
 **/

Container::register("Session", "Session", "../session/Session.php",Container::Singleton());
Container::register("SessionObject", "SessionObject", "../session/SessionObject.php",Container::Common());




/**
 * JScript Validators
 * 
 **/

Container::register("JQueryValidator", "BasicJQueryValidator", "../jscript/JQueryValidators/BasicJQueryValidator.php",Container::Common());

Container::register("LenJSValidator", "JQLen", "../jscript/JQueryValidators/JQLen.php",Container::Common());
Container::register("RequiredJSValidator", "JQRequired", "../jscript/JQueryValidators/JQRequired.php",Container::Common());


Container::register("NumericJSValidator", "JQNumeric", "../jscript/JQueryValidators/JQTypes.php",Container::Common());
Container::register("AlphabeticJSValidator", "JQAlphabetic", "../jscript/JQueryValidators/JQTypes.php",Container::Common());
Container::register("AlphanumericJSValidator", "JQAlphanumeric", "../jscript/JQueryValidators/JQTypes.php",Container::Common());
Container::register("EmailJSValidator", "JQEmail", "../jscript/JQueryValidators/JQTypes.php",Container::Common());
Container::register("PasswordJSValidator", "JQPassword", "../jscript/JQueryValidators/JQTypes.php",Container::Common());
Container::register("UrlJSValidator", "JQUrl", "../jscript/JQueryValidators/JQTypes.php",Container::Common());
Container::register("DateJSValidator", "JQDate", "../jscript/JQueryValidators/JQTypes.php",Container::Common());












