<?php

    include("_config_inc.php");//catch project position, use for security
    include("admin/action/db.php");
    $db = new Db;
    $BASE_PATH = BASE_PATH;
    //base path : catch path folder project, use with in include
    $BASE_URL = BASE_URL;
    //base uel : catch localhost project on search bar chrome, use with link
    $home_active = "active";
    $mid = 0;
    $s=0;
    $e=2;
    $totalData = 0;
    $fbUrl = $BASE_URL;
    $fbTitle = "Rean Web";
    $fbImg = $BASE_URL.'home/img/1.jpg';
    if(isset($_GET['nid'])) {
        $mid = $_GET['mid'];
        $nid = $_GET['nid'];

        // function get_current_data($fld, $tbl, $con) {
        //     $sql = "SELECT $fld FROM $tbl WHERE $con";
        //     $rs = $this->cn->query($sql);
        //     $row = $rs->fetch_array();
        //     return $row;
        // }   


        // $row = $db->get_current_data("title,img", "tbl_news", "id = '$nid'");
        $row = $db->get_current_data("title,img", "tbl_news", "id = $nid");
        $fbTitle = $row[0]; 
        $fbImg = $BASE_URL.'admin/img/'.$row[1];
        $fbUrl = $BASE_URL."?mid=$mid&nid=$nid";
    }else if( isset($_GET['mid']) ) {
        $home_active = "";
        $mid = $_GET['mid'];
        $row_count = $db->count_data("tbl_news", "mid", $mid, 0);
        $totalData = $row_count - $e;
        $row = $db->get_current_data("title, img", "tbl_menu", "id = $mid");
        $fbTitle = $row[0];
        $fbImg = $BASE_URL.'admin/img/'.$row[1];
        $fbUrl = $BASE_URL."?mid=$mid";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- You can use Open Graph tags to customize link previews.
    Learn more: https://developers.facebook.com/docs/sharing/webmasters -->
    <meta property="og:url"           content="<?php echo $fbUrl; ?>" />
    <meta property="og:type"          content="website" />
    <meta property="og:title"         content="<?php echo $fbTitle; ?>" />
    <meta property="og:description"   content="Your description" />
    <meta property="og:image"         content="<?php echo $fbImg; ?>" />

    <title>Home Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="<?php echo $BASE_URL; ?>home/style/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $BASE_URL; ?>home/style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <script src="home/js/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v15.0" nonce="FnHSpGsB"></script>
    <?php
        include($BASE_PATH."home/file/menu.php");
        if(isset($_GET['nid'])) {
            $nid = $_GET['nid'];
            include($BASE_PATH."home/file/news-detail.php");
        } else if ( isset($_GET['mid']) ) {
            $mid = $_GET['mid'];
            include($BASE_PATH."home/file/news-menu.php");
        } else {
            include($BASE_PATH."home/file/slide.php");
            include($BASE_PATH."home/file/news.php");
        }

    ?>
</body>
<script>
    var baseUrl = "<?php echo $BASE_URL; ?>";
    var mid = "<?php echo $mid; ?>";
    var s = "<?php echo $s + $e; ?>";
    var e = "<?php echo $e; ?>";
    var totalData = "<?php echo $totalData; ?>";
</script>
<script>
    $(document).ready(function() {
        $(".btnMoreNews").click(function() {
            var eThis = $(this);
            $.ajax({
                url:baseUrl+'home/action/get-news.php',
                type:'POST',
                data:{
                    s: s,
                    e: e,
                    mid: mid
                },
                // contentType:false,
                cache:false,
                // processData:false,
                dataType:"json",
                beforeSend:function(){
                        //work before success  
                        eThis.html("Wait...");
                        eThis.css({"pointer-events" : "none"});
                },
                success:function(data){   
                        //work after success: 
                    var txt = "";
                    for(i in data) {
                        txt += `
                            <a href="${baseUrl}?mid=${data[i]['mid']}&nid=${data[i]['id']}" class="box">
                                <div class="img-box">
                                    <img src="admin/img/${data[i]['img']}" alt="">
                                </div>
                                <div class="txt-box">
                                    <p>${data[i]['title']}</p>
                                    <p>${data[i]['id']}</p>
                                </div>
                            </a>
                        `;
                    }
                    eThis.parent().before(txt);
                    s = parseInt(s) + parseInt(e);
                    totalData -= e;
                    if(totalData <= 0) {
                        eThis.hide();
                    }
                    eThis.html("See More...");
                    eThis.css({"pointer-events" : "auto"});
                }				
            }); 
        });
    });
</script>
</html>

