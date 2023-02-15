<?php
function cors()
{
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Origin, Authorization, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
            header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
        }

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
            header("Access-Control-Allow-Headers: Origin, Authorization, X-Requested-With, Content-Type, Accept");
        }

        exit(0);
    }
}
cors();

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE);
function check_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$title=check_input($_POST['title']);
$annotation=check_input($_POST['annotation']);
$content=check_input($_POST['content']);
$email=check_input($_POST['email']);
$views=check_input($_POST['views']);
$date=check_input($_POST['date']);
$publish_in_index=check_input($_POST['publish_in_index']);
$category=check_input($_POST['category']);
$error = array();

//[,['type' => 'annotation', 'message' => ''too small]];
if(empty($title) ||strlen($title) <= 3 || strlen($title) >= 255)
{
    $error[] = ['type' =>"title",'message' => "too long"];

}
if(empty($annotation)||strlen($annotation) > 500){

    $error[] = ['type' => 'annotation', 'message' => "Annotation don't have more 500 symbols"];

}
if(empty($content) ||strlen($content) > 30000 ) {
    $error[] = ['type' => 'content', 'message' => "Content don't have more 30000 symbols"];
}
if($email==''){

    $error[]=['type' => 'email','message' => "Email is required"];
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL))
{

    $error[]=['type' => 'email','message' => "Invalid email format"];
}
if (!is_numeric($views) || $views < 0 || !filter_var($views, FILTER_VALIDATE_INT)) {
    $errors["views"] = "Count views must be integer ano > 0";
}

if( $date > date('Y-m-d'))
{
    $error[]=['type' => 'date','message' => "Date is not valid or dimm current date"];

}
if( !is_int($category) || $category == -1)
{
    $error[]=['type' => 'category','message' => "Please select one category on the List"];
}
if(empty($publish) )
{
    $error[] = ['type' => 'publish_in_index', 'message' =>"No radio buttons were checked."];
}




header("Content-type: application/json");
echo json_encode($error);
die();
?>