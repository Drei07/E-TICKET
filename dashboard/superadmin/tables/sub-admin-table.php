<table class="table table-bordered table-hover">
<?php

require_once '../authentication/superadmin-class.php';
include_once __DIR__.'/../../../database/dbconfig2.php';

$user = new SUPERADMIN();
if(!$user->isUserLoggedIn())
{
 $user->redirect('../../../private/superadmin/');
}



function get_total_row($pdoConnect)
{
  $pdoQuery = "SELECT COUNT(*) as total_rows FROM users WHERE user_type = :user_type";
  $pdoResult = $pdoConnect->prepare($pdoQuery);
  $pdoResult->execute(array(":user_type" => 2));
  $row = $pdoResult->fetch(PDO::FETCH_ASSOC);
  return $row['total_rows'];
}

$total_record = get_total_row($pdoConnect);
$limit = '20';
$page = 1;
if(isset($_POST['page']))
{
  $start = (($_POST['page'] - 1) * $limit);
  $page = $_POST['page'];
}
else
{
  $start = 0;
}

$query = "
SELECT * FROM users WHERE user_type = :user_type
";
$output = '';
if($_POST['query'] != '')
{
  $query .= '
  AND first_name LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  OR last_name LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  OR middle_name LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  OR email LIKE "%'.str_replace(' ', '%', $_POST['query']).'%"
  ';
}

$query .= 'ORDER BY id ASC ';

$filter_query = $query . 'LIMIT '.$start.', '.$limit.'';

$statement = $pdoConnect->prepare($query);
$statement->execute(array(":user_type" => 2));
$total_data = $statement->rowCount();

$statement = $pdoConnect->prepare($filter_query);
$statement->execute(array(":user_type" => 2));
$total_filter_data = $statement->rowCount();

if($total_data > 0)
{
$output = '
    <div class="row-count">
      Showing ' . ($start + 1) . ' to ' . min($start + $limit, $total_data) . ' of ' . $total_record . ' entries
    </div>
    <thead>
    <th>STATUS</th>
    <th>DEPARTMENT</th>
    <th>NAME</th>
    <th>PHONE NUMBER</th>
    <th>EMAIL</th>
    <th>ACTIONS</th>
    </thead>
';
  while($row=$statement->fetch(PDO::FETCH_ASSOC))
  {

    if ($row["account_status"] == "active") {
      $button = '<button type="button" class="btn btn-danger V"><a href="controller/user-controller?id='.$row["id"].'&disabled_sub_admin=1" class="delete"><i class="bx bxs-trash"></i></a></button>';
      $status = '<button type="button" class="btn btn-success V" style="width: 80px;">Active</button>';
    
    } else if ($row["account_status"] == "disabled") {
      $button = '<button type="button" class="btn btn-warning V"><a href="controller/user-controller?id='.$row["id"].'&activate_sub_admin=1" class="activate">Activate</a></button>';
      $status = '<button type="button" class="btn btn-danger V" style="width: 80px;">Disabled</button>';
    }

    $department_id = $row['department'];
    $pdoQuery = "SELECT * FROM department WHERE id = :id";
    $pdoResult = $pdoConnect->prepare($pdoQuery);
    $pdoExec = $pdoResult->execute(array(":id" => $department_id));
    $department_data = $pdoResult->fetch(PDO::FETCH_ASSOC);

    $output .= '
    <tr>
      <td>'.$status.'</td>
      <td>'.$department_data["department"].'</td>
      <td>'.$row["last_name"].', '.$row["first_name"].' '.$row["middle_name"].'</td>
      <td>'.$row["phone_number"].'</td>
      <td>'.$row["email"].'</td>
      <td>
      <button type="button" class="btn btn-primary V"><a href="edit-sub-admin?id='.$row["id"].'" class="edit"><i class="bx bxs-edit"></i></a></button>
      '.$button.'
      </td>        
    </tr>
    ';
  }
}
else
{
  echo '<h1>No Data Found</h1>';
}

$output .= '
</table>
<div align="center">
  <ul class="pagination">
';

$total_links = ceil($total_data/$limit);
$previous_link = '';
$next_link = '';
$page_link = '';

//echo $total_links;

if($total_links > 5)
{
  if($page < 5)
  {
    for($count = 1; $count <= 5; $count++)
    {
      $page_array[] = $count;
    }
    $page_array[] = '...';
    $page_array[] = $total_links;
  }
  else
  {
    $end_limit = $total_links - 5;
    if($page > $end_limit)
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $end_limit; $count <= $total_links; $count++)
      {
        $page_array[] = $count;
      }
    }
    else
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $page - 1; $count <= $page + 1; $count++)
      {
        $page_array[] = $count;
      }
      $page_array[] = '...';
      $page_array[] = $total_links;
    }
  }
}
else
{
  $page_array[] = '...';
  for($count = 1; $count <= $total_links; $count++)
  {
    $page_array[] = $count;
  }
}

for($count = 0; $count < count($page_array); $count++)
{
  if($page == $page_array[$count])
  {
    $page_link .= '
    <li class="page-item active">
      <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only"></span></a>
    </li>
    ';

    $previous_id = $page_array[$count] - 1;
    if($previous_id > 0)
    {
      $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a></li>';
    }
    else
    {
      $previous_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Previous</a>
      </li>
      ';
    }
    $next_id = $page_array[$count] + 1;
    if($next_id > $total_links)
    {
      $next_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Next</a>
      </li>
        ';
    }
    else
    {
      $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a></li>';
    }
  }
  else
  {
    if($page_array[$count] == '...')
    {
      $page_link .= '
      <li class="page-item disabled">
          <a class="page-link" href="#">...</a>
      </li>
      ';
    }
    else
    {
      $page_link .= '
      <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
      ';
    }
  }
}

$output .= $previous_link . $page_link . $next_link;
$output .= '
  </ul>

</div>
';

echo $output;

?>

<script src="../../src/node_modules/sweetalert/dist/sweetalert.min.js"></script>
<script src="../../src/js/form.js"></script>

</table>