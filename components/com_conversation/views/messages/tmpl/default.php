<?php
/**
 * @version     1.0.0
 * @package     com_conversation
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      shahriar <apachish@gmail.com> - http://www.bmsystem.ir
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$user = JFactory::getUser();




?>
<!--<script src="/conver/components/com_conversation/assets/js/jquery-1.9.1.js"></script>-->
<script src="components/com_conversation/assets/js/ui/jquery.ui.core.js"></script>
<script src="components/com_conversation/assets/js/ui/jquery.ui.widget.js"></script>
<script src="components/com_conversation/assets/js/ui/jquery.ui.tabs.js"></script>
<link rel="stylesheet" href="components/com_conversation/assets/css/demos.css">
<link rel="stylesheet" href="components/com_conversation/assets/css/base/jquery.ui.all.css">
<script>
var g_days=[31,28,31,30,31,30,31,31,30,31,30,31], j_days=[31,31,31,31,31,31,30,30,30,30,30,29];
function gregorianToJalali(g_y, g_m, g_d)
{
    g_y = parseInt(g_y);
    g_m = parseInt(g_m);
    g_d = parseInt(g_d);
    var gy = g_y-1600;
    var gm = g_m-1;
    var gd = g_d-1;
    var g_day_no = 365*gy+parseInt((gy+3) / 4)-parseInt((gy+99)/100)+parseInt((gy+399)/400);
    for (var i=0; i < gm; ++i)
        g_day_no += g_days[i];
    if (gm>1 && ((gy%4==0 && gy%100!=0) || (gy%400==0)))
        ++g_day_no;
    g_day_no += gd;
    var j_day_no = g_day_no-79;
    var j_np = parseInt(j_day_no/ 12053);
    j_day_no %= 12053;
    var jy = 979+33*j_np+4*parseInt(j_day_no/1461);
    j_day_no %= 1461;
    if(j_day_no >= 366)
    {
        jy += parseInt((j_day_no-1)/ 365);
        j_day_no = (j_day_no-1)%365;
    }
    for(var i = 0; i < 11 && j_day_no >= j_days[i]; ++i)
        j_day_no -= j_days[i];
    var jm = i+1;
    var jd = j_day_no+1;
    if(jm<10){
        jm='0'+jm;}
         if(jd<10){
        jd='0'+jd;}
    return [jy,jm,jd];
}
    jQuery(function() {
        //create a new WebSocket object.
        var wsUri = "ws://37.59.166.168:9000/server.php";
        var opt;
        websocket = new WebSocket(wsUri);

        websocket.onopen = function(ev) { // connection is open
            jQuery('.messages-list').append("<div class=\"system_msg\" style='display: none'>Connected!</div>"); //notify user
        }
        jQuery( "#tabs" ).tabs();
        function setbg(color)
        {
            document.getElementById("styled").style.background=color
        }
        jQuery('.loadmore').live('click',function(){
            var dataalias=jQuery(this).attr('data-alias');
                        var lastid=jQuery(this).attr('data-id');
            var group=jQuery(this).attr('data-group');
                        var usernow='<?php echo $user->id;?>';
                        var userlast;var timelast;var datelast;
                        jQuery('.message_load').hide();
            jQuery.ajax(
                {
                    url : "index.php?option=com_conversation&task=loadmoremessage",
                    type: "POST",
                    data : {group:group,lastid:lastid,numbermessage:dataalias},
                    success:function(data, textStatus, jqXHR)
                    {
                        console.log(jQuery.parseJSON(data));
                         var ret = jQuery.parseJSON(data);console.log(ret['exist']);
                        //console.log(ret[0].Name);// alert(ret.state); alert(data);
                        opt='';
                        if(ret['exist']){
                        opt+='<div class="loadmore" data-id="'+ret['query'][0].id+'"';
                        opt+='data-alias="'+ret['exist']+'"';
                        opt+='data-group="'+ret['query'][0].team+'">';
                        opt+='<span class="message_load">دیدن پیام های گذشته</span>';
                        opt+='</div>';}
                        jQuery.each(ret['query'] ,function(_index, _val){
                            //alert( _val.Name);
                            var year=_val.create_time.substr(0,4);
                            var month=_val.create_time.substr(5,2);
                            var day=_val.create_time.substr(8,2);
                            var data = String(gregorianToJalali(year, month, day)).replace(',','/').replace(',','/');
                            opt=opt+'<li class="messege-row">';
                            if(data != datelast){
                                opt+='<div class="date_message">'+data+'</div>';
                            }
                            opt+='<ul class="message-list';
                if(_val.sender==usernow){
                    opt+=' sent-message '
                }else{
                    opt+=' received-message '
                }
                //opt+=' last_ul'+_val.team;
                opt+='">';
                opt+='<li class="message-single';
                if(userlast != _val.sender ||  (_val.checked_out-timelast)>60){
                opt+='  message-first';}
                opt+='">';
                opt+='<div class="avatar-container">';
                opt+='<img src="components/com_conversation/assets/img/avatars/1.jpg">';
                opt+='</div>';
                opt+='<div class="message-container">';
                opt+='<div class="message-text">';
                opt+='<p class="time_message"><?php
                            echo $user->name;

                            ?></p><p>';
                opt+=_val.message;
                opt+='</p><p align="left" class="time_message"><span>'+_val.create_time.substr(11, 5)+'</span</p></div>';
                opt+='</div>';
                opt+='</li><p class="time"><div class="like" title="<?php echo JText::_('TITLE_LIKE');?>" id="like_'+_val.id+'" ></div><span id="num'+_val.id+'"  class="likenumber">0</span>  <div class="unlike" title="<?php echo JText::_('TITLE_UNLIKE');?>" id="unlike_'+_val.id+'" ></div>';
                opt+='<span id="numunlike'+_val.id+'"  class="unlikenumber">0</span>  <span>.:'+_val.id+':.</span></p></ul></li>';
                userlast=_val.sender;
                timelast=_val.checked_out;
                datelast=data;

                        });
                        jQuery('.loadmore').append(opt);
                        jQuery('.loadmore').attr('data-alias',ret['exist']);
                        jQuery('.loadmore').attr('data-id',ret['query'][0].id);
                        jQuery('.loadmore').attr('data-group',ret['query'][0].team);
                        if(ret['exist']==0){
                            jQuery('.message_load').text('');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        //alert(jqXHR);
                        //if fails
                    }
                });       
      });
        function loaditem(group){
            jQuery.ajax(
                {
                    url : "index.php?option=com_conversation&task=loadmessage",
                    type: "POST",
                    data : {group:group},
                    success:function(data, textStatus, jqXHR)
                    {
                        console.log(data);

                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        //alert(jqXHR);
                        //if fails
                    }
                });
            e.preventDefault(); //STOP default action
//            e.unbind(); //unbind. to stop multiple form submit.
        }
        jQuery(".ajaxform").live('submit',function(e)
        {
            var postData = jQuery(this).serializeArray();
            var formURL = jQuery(this).attr("action");
            var formURL = jQuery(this).val("action");


            //prepare json data

            jQuery.ajax(
                {
                    url : "index.php?option=com_conversation&task=upmessage",
                    type: "POST",
                    data : postData,
                    success:function(data, textStatus, jqXHR)
                    {
                        var ret = jQuery.parseJSON(data);
                        var msg = {
                            message: ret.message,
                            name: ret.groups,
                            color: ret.id,
                            type:ret.last,
                            user:ret.userid

                        };
                        //convert and send data to server
                        websocket.send(JSON.stringify(msg));

                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        //alert(jqXHR);
                        //if fails
                    }
                });
            e.preventDefault(); //STOP default action
//            e.unbind(); //unbind. to stop multiple form submit.
        });
        //#### Message received from server?
        websocket.onmessage = function(ev) {
            var msg = JSON.parse(ev.data); //PHP sends Json data
            var groups = msg.name; //message type
            var message = msg.message; //message text
            var  id = msg.color; //user name
            var last = msg.type; //color
            var user = msg.user; //color
            var usernow='<?php echo $user->id;?>';
            var img='<?php echo $im=($this->get_image($user->id)?$this->get_image($user->id):'components/com_conversation/assets/img/avatars/1.jpg')?>';
            if(last){
                opt='<li class="message-single">';
                opt+='<div class="avatar-container">';
                opt+='<img src="'+img+'">';
                opt+='</div>';
                opt+='<div class="message-container">';
                opt+='<div class="message-text">';
                opt+='<p class="time_message" class="time_message"><?php
                            //echo $user->name;
                            ?></p><p>';
                opt+=message;
                opt+='</p><p align="left"><?php  echo '<span>  '.date("H:i").'</span>'; ?></p></div>';
                opt+='</div>';
                opt+='</li><p class="time"><div class="like" id="like_'+id+'" title="<?php echo JText::_('TITLE_LIKE');?>" ></div><span id="num'+id+'"  class="likenumber">0</span>  <div class="unlike" id="unlike_'+id+'" title="<?php echo JText::_('TITLE_UNLIKE');?>"></div>';
                opt+='<span id="numunlike'+id+'"  class="unlikenumber">0</span>  <span>.:'+id+':.</span></p>';
                            jQuery('.last_ul'+groups).append(opt);
            }else{
                jQuery('.message-list').removeClass('last_ul'+groups);
                opt='<li class="messege-row"><ul class="message-list';
                if(user==usernow){
                    opt+=' sent-message '
                }else{
                    opt+=' received-message '
                }
                opt+=' last_ul'+groups+'">';
                opt+='<li class="message-single message-first">';
                opt+='<div class="avatar-container">';

                opt+='<img src="'+img+'">';
                opt+='</div>';
                opt+='<div class="message-container">';
                opt+='<div class="message-text">';
                opt+='<p class="time_message"><?php
                            //echo $user->name;

                            ?></p><p>';
                opt+=message;
                opt+='</p><p align="left" class="time_message"><?php  echo '<span>  '.date("H:i").'</span>'; ?></p></div>';
                opt+='</div>';
                opt+='</li><p class="time"><div class="like" title="<?php echo JText::_('TITLE_LIKE');?>" id="like_'+id+'" ></div><span id="num'+id+'"  class="likenumber">0</span>  <div class="unlike" title="<?php echo JText::_('TITLE_UNLIKE');?>" id="unlike_'+id+'" ></div>';
                opt+='<span id="numunlike'+id+'"  class="unlikenumber">0</span>  <span>.:'+id+':.</span></p></ul></li>';

                jQuery('.group'+groups).append(opt);

            }



            jQuery('#styled').val(''); //reset text
        };

        websocket.onerror	= function(ev){jQuery('.messages-list').append("<div class=\"system_error\">Error Occurred - "+ev.data+"</div>");};
        websocket.onclose 	= function(ev){jQuery('.messages-list').append("<div class=\"system_msg\">Connection Closed</div>");};
        jQuery(".like").live('click',function()
        {
            var likeid = jQuery(this).attr("id");
            likeid=strstr(likeid,'_');
            var res = likeid.replace("_", "");
            jQuery.ajax(
                {
                    url : "index.php?option=com_conversation&task=liked",
                    type: "POST",
                    data :{like:res},
                    success:function(data, textStatus, jqXHR)
                    {
                        if(data){
                            jQuery('#like_'+res).removeClass('like');
                            jQuery('#like_'+res).addClass('liked');
                            jQuery('#unlike_'+res).hide();
                            jQuery('#num'+res).html(data);
                        }else{
                            alert('خطا در اجرا');
                        }


                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        //alert(jqXHR);
                        //if fails
                    }
                });
            e.preventDefault(); //STOP default action
//            e.unbind(); //unbind. to stop multiple form submit.
        });

        jQuery(".liked").live('click',function()
        {
            var likeid = jQuery(this).attr("id");
            likeid=strstr(likeid,'_');
            var res = likeid.replace("_", "");
            jQuery.ajax(
                {
                    url : "index.php?option=com_conversation&task=likedd",
                    type: "POST",
                    data :{like:res},
                    success:function(data, textStatus, jqXHR)
                    {
                        if(data){
                            jQuery('#like_'+res).removeClass('liked');
                            jQuery('#like_'+res).addClass('like');
                            jQuery('#unlike_'+res).show();
                            jQuery('#num'+res).html(data);
                        }else{
                            alert('خطا در اجرا');
                        }


                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        //alert(jqXHR);
                        //if fails
                    }
                });
            e.preventDefault(); //STOP default action
//            e.unbind(); //unbind. to stop multiple form submit.

        });
        jQuery(".unlike").live('click',function()
        {
            var likeid = jQuery(this).attr("id");
            likeid=strstr(likeid,'_');
            var res = likeid.replace("_", "");
            jQuery.ajax(
                {
                    url : "index.php?option=com_conversation&task=unliked",
                    type: "POST",
                    data :{like:res},
                    success:function(data, textStatus, jqXHR)
                    {
                        if(data){
                            jQuery('#unlike_'+res).removeClass('unlike');
                            jQuery('#unlike_'+res).addClass('unliked');
                            jQuery('#like_'+res).hide();
                            jQuery('#numunlike'+res).html(data);
                        }else{
                            alert('خطا در اجرا');
                        }


                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        //alert(jqXHR);
                        //if fails
                    }
                });
            e.preventDefault(); //STOP default action
//            e.unbind(); //unbind. to stop multiple form submit.
        });

        jQuery(".unliked").live('click',function()
        {
            var likeid = jQuery(this).attr("id");
            likeid=strstr(likeid,'_');
            var res = likeid.replace("_", "");
            jQuery.ajax(
                {
                    url : "index.php?option=com_conversation&task=unlikedd",
                    type: "POST",
                    data :{like:res},
                    success:function(data, textStatus, jqXHR)
                    {
                        if(data){
                            jQuery('#unlike_'+res).removeClass('unliked');
                            jQuery('#unlike_'+res).addClass('unlike');
                            jQuery('#like_'+res).show();
                            jQuery('#numunlike'+res).html(data);
                        }else{
                            alert('خطا در اجرا');
                        }


                    },
                    error: function(jqXHR, textStatus, errorThrown)
                    {
                        //alert(jqXHR);
                        //if fails
                    }
                });
            e.preventDefault(); //STOP default action
//            e.unbind(); //unbind. to stop multiple form submit.

        });
        jQuery("#tabs ul li").focusin(function(){
            var group=jQuery(this).find('a').attr('id');
            console.log(group);
            loaditem(group);
        })
        jQuery(".ajaxform").focusin(function(){
            var group=jQuery(this).find('input[name="groups"]').val();
            console.log(group);
            loaditem(group)

        })
        function strstr (haystack, needle, bool) {
            // http://kevin.vanzonneveld.net
            // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
            // +   bugfixed by: Onno Marsman
            // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
            // *     example 1: strstr('Kevin van Zonneveld', 'van');
            // *     returns 1: 'van Zonneveld'
            // *     example 2: strstr('Kevin van Zonneveld', 'van', true);
            // *     returns 2: 'Kevin '
            // *     example 3: strstr('name@example.com', '@');
            // *     returns 3: '@example.com'
            // *     example 4: strstr('name@example.com', '@', true);
            // *     returns 4: 'name'
            var pos = 0;
            haystack += '';
            pos = haystack.indexOf(needle);
            if (pos == -1) {
                return false;
            } else {
                if (bool) {
                    return haystack.substr(0, pos);
                } else {
                    return haystack.slice(pos);
                }
            }
        }
    });


</script>
<!--var ret = jQuery.parseJSON(data);-->
<!--var opt;-->
<!--//console.log(ret[0].Name);// alert(ret.state); alert(data);-->
<!--$.each(ret ,function(_index, _val){-->
<!--//alert( _val.Name);-->
<!---->
<!--opt=opt+"<option value='"+_val.City+"'>"+_val.Name+"</option> ";-->
<!--});-->
<!--jQuery('.city').html(opt);-->
<style type="text/css">
    .message_load{
        width: 100%;
        height: 120px;
        border: 3px solid #cccccc;
        padding: 5px;
        font-family: Tahoma, sans-serif;
        background-position: bottom right;
        background-repeat: no-repeat;
    }
    textarea#styled {
        width: 100%;
        height: 120px;
        border: 3px solid #cccccc;
        padding: 5px;
        font-family: Tahoma, sans-serif;
        background-position: bottom right;
        background-repeat: no-repeat;
    }
    p.time{
        font-size: 10px;
        text-align: left;
        display: inline;
    }
    p.time_message{
        font-size: 10px;
        text-align: right;
        direction: rtl;
        display: inline;
    }
    .like{
        background: url('components/com_conversation/assets/img/like.png');
        width: 20px;
        height: 20px;
        display: inline-block;
    }
    .liked{
        background: url('components/com_conversation/assets/img/liked.png');
        width: 20px;
        height: 20px;
        display: inline-block;
    }
    .unlike{
        background: url('components/com_conversation/assets/img/unlike.png');
        width: 20px;
        height: 20px;
        display: inline-block;
    }
    .unliked{
        background: url('components/com_conversation/assets/img/unliked.png');
        width: 20px;
        height: 20px;
        display: inline-block;
    }
    .unlikenumber{
        color:#960004;
    }
    .likenumber{
        color:#008844;

    }
    a:link {color: white; }
    a:hover { color:#0000ff;cursor:pointer;  }
    .date_message{
        text-align: center;

    }
</style>

<link rel="shortcut icon" type="image/x-icon" href="components/com_conversation/assets/img/favicon.ico">
<link rel="stylesheet" href="components/com_conversation/assets/css/styles.css">

<?php
$groupsize=sizeof($user->groups);
$access_group=$this->access_text();

echo '<div id="tabs" class="main_contain">
                <ul>';
foreach($user->groups as $grops){
    if(in_array($grops,$this->list_top_group())){
        $namegroup=$this->getgroup_user($grops);
        echo    '<li dir="rtl"><a href="#groupp'.$grops.'" id="'.$grops.'">'.$namegroup->title;
        $numberitem=$this->getnumbermessage($grops);
        if($numberitem)
        echo ' <span id="num_gruop_'.$grops.'">('.$numberitem.')</span>';
        echo '</a></li>';
    }
}
echo '</ul>';
foreach($user->groups as $grops){
    if(in_array($grops,$this->list_top_group())){
        echo '<div id="groupp'.$grops.'">';
        ?>

        <body>
        <div class="messages-wrapper">
            <ul class="messages-list group<?php echo $grops;?>">
                <?php
                    $userlast=0;
                    $daylast=0;
                    $timelast=0;
                    $number=0;
                    $messages=$this->getmessage($grops);$allmessage=$this->getnumberallmessage($grops);
                ?>
                    <div class="loadmore" data-id="<?php echo $messages[0]->id;?>" 
                         data-alias="<?php echo $allmessage-sizeof($messages);?>"
                         data-group="<?php echo  $grops;?>"
                         >
                    <span class='message_load'> دیدن پیام های گذشته</span>
                    </div>
                <?php
                $size=sizeof($messages);
                foreach($messages as $message){
                $year=substr($message->create_time,0,4);
                $month=substr($message->create_time,5,2);
                $day=substr($message->create_time,8,2);

                 $date=$this->hijricalender($year,$month,$day);
               

                if($message->created_by!=$userlast || ($message->checked_out-$timelast)>60){
                    if($number){
                        echo '</ul></li>';}
                    $i=0;
                }
                if($user->id == $message->created_by ){
                if(!$i)
                {
                ?>
                <li class="messege-row">
                     <?php if($date!=$datelast)
                    echo '<div class="date_message">'.$date.'</div>';?>
                    <ul class="message-list sent-message <?php if(!$message->father){ echo 'last_ul'.$message->team;  }?>">
                        <?php }?>
                        <li class="message-single <?php if(!$i){?>message-first<?php }?>">
                            <div class="avatar-container">
                                <?php 
                                $url_image=($this->get_image($user->id)?$this->get_image($user->id):'components/com_conversation/assets/img/avatars/1.jpg')?>
                                <img src="<?php echo $url_image; ?>">
                            </div>
                            <div class="message-container">
                                <div class="message-text" >
                                    <?php
                                    echo '<p class="time_message">';
                                        echo '</p>';
                                     echo '<p>'.$message->message.'</p>';
                                            echo '<p class="time_message" align="left"><span>  '.$time=substr($message->create_time,10,6).'</span></p>';

                                    ?>
                                </div>
                            </div>
                        </li>
                        <?php

                        echo '<p class="time">';
                        if(in_array($message->id,$this->get_like($user->id))){
                            echo '<div class="liked" title="'.JText::_('TITLE_LIKE').'" id="like_'.$message->id.'" ></div>';
                        }else{
                            echo '<div class="like" title="'.JText::_('TITLE_LIKE').'" id="like_'.$message->id.'" ></div>';
                        }
                        echo '<span id="num'.$message->id.'"  class="likenumber">'.$message->agree.'</span>&nbsp;&nbsp;';
                        if(in_array($message->id,$this->get_unlike($user->id))){
                            echo '<div class="unliked" title="'.JText::_('TITLE_UNLIKE').'" id="unlike_'.$message->id.'" ></div>';
                        }else{
                            echo '<div class="unlike" title="'.JText::_('TITLE_UNLIKE').'" id="unlike_'.$message->id.'" ></div>';
                        }
                        echo '<span id="numunlike'.$message->id.'"  class="unlikenumber">'.$message->opposition.'</span>&nbsp;&nbsp;';
                        echo '<span>.:'.$message->id.':.</span>';
                        echo '</p>';
                        ?>
                        <?php
                        }else{
                        if(!$i){
                        ?>
                        <li class="messege-row">
                            <ul class="message-list received-message <?php if(!$message->father){ echo 'last_ul'.$message->team;  }?>">
                                <?php }?>
                                <li class="message-single <?php if(!$i){?>message-first<?php }?>">
                                    <div class="avatar-container">
 <?php 
                            $userr =& JFactory::getUser($message->sender);
                                $url_image=($this->get_image($userr->id)?$this->get_image($userr->id):'components/com_conversation/assets/img/avatars/1.jpg')?>
                                <img src="<?php echo $url_image; ?>">                                    </div>
                                    <div class="message-container">
                                        <div class="message-text" >
                                            <?php
                                            echo '<p class="time_message">';

                                            echo $userr->name;

                                            echo '</p>';
                                            echo '<p>'.$message->message.'</p>';
                                            echo '<p class="time_message" align="left"><span>  '.$time=substr($message->create_time,10,6).'</span></p>';

                                            ?>


                                        </div>
                                    </div>
                                </li>
                                <?php

                                echo '<p class="time">';
                                if(in_array($message->id,$this->get_like($user->id))){
                                    echo '<div class="liked" title="'.JText::_('TITLE_LIKE').'" id="like_'.$message->id.'" ></div>';
                                }else{
                                    echo '<div class="like" title="'.JText::_('TITLE_LIKE').'" id="like_'.$message->id.'" ></div>';
                                }
                                echo '<span id="num'.$message->id.'"  class="likenumber">'.$message->agree.'</span>&nbsp;&nbsp;';
                                if(in_array($message->id,$this->get_unlike($user->id))){
                                    echo '<div class="unliked" title="'.JText::_('TITLE_UNLIKE').'" id="unlike_'.$message->id.'" ></div>';
                                }else{
                                    echo '<div class="unlike" title="'.JText::_('TITLE_UNLIKE').'" id="unlike_'.$message->id.'" ></div>';
                                }
                                echo '<span id="numunlike'.$message->id.'" class="unlikenumber">'.$message->opposition.'</span>&nbsp;&nbsp;';
                                echo '<span>.:'.$message->id.':.</span>';

                                echo '</p>';


                                }
                                $i++;
                                $userlast=$message->created_by;
                                $daylast=$message->create_time;
                                $timelast=$message->checked_out;
                                $datelast=$date;
                                ++$number;
                                if($number==$size)
                                    echo '</ul></li>';
                                }?>



                            </ul>
        </div>
        <div class="input-wrapper">

        </div>

<?php if(in_array($grops,$access_group)){?>

        <br>
        <form name="ajaxform" class="ajaxform" action="#" method="POST" enctype="multipart/form-data" target="RSS_target" onsubmit="starting();" accept-charset="utf-8" >
            <textarea name="styled-textarea" id="styled" onfocus="this.value=''; setbg('#e5fff3');" onblur="setbg('white')">

            </textarea>
            <input name="groups" id="input_groups<?php echo $grops;?>" value="<?php echo $grops;?>" type="hidden">
            <input type="submit" value="<?php echo JText::_('COM_CONVERSATION_SEND')?>">
            <iframe id="RSS_target" name="RSS_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
        </form>
<?php } ?>
        </body>

        <?php
        echo '</div>';
    }}
echo '</div>';


?>


