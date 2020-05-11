<?php
$result=false;
 $query="";
if(isset($_POST['q']))
{
  include("webhose.php");
   Webhose::config("5ddd2983-e017-48b1-9d3f-47849089d781");


    
//Helper method to print result:

    function print_filterwebdata_titles($api_response)
    {
        if($api_response == null)
        {
            echo "<p>Response is null, no action taken.</p>";
            return;
        }
      
        if(isset($api_response->posts))
            foreach($api_response->posts as $post)
            {
  // echo(json_encode($post));
  //       die();
              ?>
              <div style="margin-top: 50px;" class="row">
                  
                  <div class="col-lg-2">
                    <?php
                    if(isset($post->thread->main_image))
                    {
                       if($post->thread->main_image!="")
                      {
                      echo ' <img class="img-thumbnail" src="'.$post->thread->main_image.'">';
                      }
                      else
                      {
                        echo "No Image";
                      }
                    }
                    else
                    {
                      echo "No Image";
                    }
                    ?>
                    
                  </div>

                  <div class="col-lg-10">
                    <?php

                    if(isset($post->title))
                    {

                      $string=$post->title;
                       if (strlen($string) > 50) {

                          // truncate string
                          $stringCut = substr($string, 0, 50);
                          $endPoint = strrpos($stringCut, ' ');

                          //if the string doesn't contain any space then it will cut without word basis.
                          $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                          $string .= '...';
                      }


                      echo '<a href="'.$post->url.'"><h3>'.$string.'</h3></a>';
                    }
                    else
                    {


                      $string=$post->thread->title;
                       if (strlen($string) > 50) {

                          // truncate string
                          $stringCut = substr($string, 0, 50);
                          $endPoint = strrpos($stringCut, ' ');

                          //if the string doesn't contain any space then it will cut without word basis.
                          $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                          $string .= '...';
                      }
                      echo '<a href="'.$post->url.'"><h3>'.$string.'</h3></a>';
                    } 
                    ?>
                    <p>
                    <?php
                    if(isset($post->text))
                    {

                      $string = strip_tags($post->text);
                      if (strlen($string) > 200) {

                          // truncate string
                          $stringCut = substr($string, 0, 200);
                          $endPoint = strrpos($stringCut, ' ');

                          //if the string doesn't contain any space then it will cut without word basis.
                          $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                          $string .= '...';
                      }
                      echo $string;
                      
                    }
                    else
                    {
                      echo "No text";
                    }
                    ?>
                    </p>
                  </div>
              </div>
               
                <?php
            }
    }

    
//Perform a "filterWebContent" query
    if(isset($_POST['q']))
    {
      $query=$_POST['q'];
    }
    else
    {
      $query="test";
    }
    $params = array(
  "q" => $query,
  "sort" => "crawled"
    );
    $result = Webhose::query("filterWebContent", $params);
    


}
?><!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Search</title>
  </head>
  <body>
  	<div style="background-color:black" class="header">
  		<div class="container">
  			<div class="row"><div class="col-lg-12"><h1 style="color: white">Search</h1></div></div>
  		</div>
  	</div>
  	<div class="container">

  		<div class="row ">
                        <div class="col-12 col-md-10 col-lg-8">
                        	&nbsp;
                        </div>
                    </div>

  		 	<div class="row ">
                        <div class="col-12 col-md-10 col-lg-8">
                            <form method="post" class="">
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <i class="fas fa-search h4 text-body"></i>
                                    </div>
                                    <!--end of col-->
                                    <div class="col">
                                        <input name="q" class="form-control form-control-lg form-control-borderless" value="<?php echo $query; ?>" type="search" placeholder="Search topics or keywords">
                                    </div>
                                    <!--end of col-->
                                    <div class="col-auto">
                                        <button class="btn btn-lg btn-success" type="submit">Search</button>
                                    </div>
                                    <!--end of col-->
                                </div>
                            </form>
                        </div>
                        <!--end of col-->
                    </div>

                  <div class="row ">
                        <div class="col-12 col-md-12 col-lg-12">
                          <?php
                          if($result!=false)
                          {
                            print_filterwebdata_titles($result);  
                          }
                          
                          ?>
                        </div>
                      </div>


  	</div>
   

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>