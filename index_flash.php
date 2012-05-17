<?php
require_once 'config.php';
if(!isset($_COOKIE['oauth_token'])){
    header('Location:auth_required.php?' . $_SERVER['QUERY_STRING']);
    die('Autenticação requerida.');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="author" content="Ricardo Martins" />
	<title>Upload multiplo para o Apontador</title>
	<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
		google.load("jqueryui", "1.8.7");
	</script>
	<!-- <script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script> -->
	<script type="text/javascript" src="js/swfupload/swfupload.js"></script>
	<script type="text/javascript" src="js/jquery.swfupload.js"></script>
	<link href="css/ui-lightness/jquery-ui-1.8.5.custom.css" rel="stylesheet" type="text/css"/>
	<link href="css/style.css" rel="stylesheet" type="text/css"/>
	
	<SCRIPT LANGUAGE=JavaScript1.1>
		<!--
		var MM_contentVersion = 6;
		var plugin = (navigator.mimeTypes && navigator.mimeTypes["application/x-shockwave-flash"]) ? navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin : 0;
		if ( plugin ) {
				var words = navigator.plugins["Shockwave Flash"].description.split(" ");
				for (var i = 0; i < words.length; ++i)
				{
				if (isNaN(parseInt(words[i])))
				continue;
				var MM_PluginVersion = words[i]; 
				}
			var MM_FlashCanPlay = MM_PluginVersion >= MM_contentVersion;
		}
		else if (navigator.userAgent && navigator.userAgent.indexOf("MSIE")>=0 
		   && (navigator.appVersion.indexOf("Win") != -1)) {
			document.write('<SCR' + 'IPT LANGUAGE=VBScript\> \n'); //FS hide this from IE4.5 Mac by splitting the tag
			document.write('on error resume next \n');
			document.write('MM_FlashCanPlay = ( IsObject(CreateObject("ShockwaveFlash.ShockwaveFlash." & MM_contentVersion)))\n');
			document.write('</SCR' + 'IPT\> \n');
		}
		if ( MM_FlashCanPlay == false) {
			//window.location.replace("<?php echo APP_URL?>index-html5.php");
		} 
		//-->
	</SCRIPT>
	
	<script type="text/javascript">  
		var url_poi = null;
		 
	    $(function(){
		
		$('#swfupload-control').swfupload({
		    upload_url: "",
		    file_post_name: 'uploadfile',
		    file_size_limit : "1024",
		    file_types : "*.jpg;*.png;*.gif",
		    file_types_description : "Image files",
		    file_upload_limit : 50,
		    flash_url : "js/swfupload/swfupload.swf",
		    button_image_url : 'js/swfupload/wdp_buttons_upload_114x29.png',
		    button_width : 114,
		    button_height : 29,
		    button_placeholder : $('#button')[0],
		    debug: <?php echo (isset($_GET['debug']))?'true':'false'?>
		})
		.bind('fileQueued', function(event, file){
		    var listitem='<li id="'+file.id+'" >'+
			'Arquivo: <em>'+file.name+'</em> ('+Math.round(file.size/1024)+' KB) <span class="progressvalue" ></span>'+
			'<div class="progressbar" ><div class="progress" ></div></div>'+
			'<p class="status" >Pendente</p>'+
			'<span class="cancel" >&nbsp;</span>'+
			'</li>';
		    $('#log').append(listitem);
		    $('li#'+file.id+' .cancel').bind('click', function(){
			var swfu = $.swfupload.getInstance('#swfupload-control');
			swfu.cancelUpload(file.id);
			$('li#'+file.id).slideUp('fast');
		    });
		    // start the upload since it's queued
		    $(this).swfupload('startUpload');
		})
		.bind('fileQueueError', function(event, file, errorCode, message){
		    alert('Tamanho do arquivo '+file.name+' é maior que o permitido');
		})
		.bind('fileDialogComplete', function(event, numFilesSelected, numFilesQueued){
		    $('#queuestatus').text('Arquivos selecionados: '+numFilesSelected+' / Arquivos na fila: '+numFilesQueued);
		})
		.bind('uploadStart', function(event, file){
		    $('#log li#'+file.id).find('p.status').text('Enviando...');
		    $('#log li#'+file.id).find('span.progressvalue').text('0%');
		    $('#log li#'+file.id).find('span.cancel').hide();
		})
		.bind('uploadProgress', function(event, file, bytesLoaded){
		    //Show Progress
		    var percentage=Math.round((bytesLoaded/file.size)*100);
		    $('#log li#'+file.id).find('div.progress').css('width', percentage+'%');
		    $('#log li#'+file.id).find('span.progressvalue').text(percentage+'%');
		})
		.bind('uploadSuccess', function(event, file, serverData){
		    var item=$('#log li#'+file.id);
		    item.find('div.progress').css('width', '100%');
		    item.find('span.progressvalue').text('100%');
		    var pathtofile='<a href="'+url_poi+'?r=' + Math.random().toString() + '" target="_blank" >view &raquo;</a>';
		    item.addClass('success').find('p.status').html('Concluido!!! | '+pathtofile);
		})
		.bind('uploadComplete', function(event, file){
		    // upload has completed, try the next one in the queue
		    $(this).swfupload('startUpload');
		})
		
		//isso é do autocomplete de cidade
		function log( message ) {
			//$( "<div/>" ).text( message ).prependTo( "#log" );
			//$( "#log" ).attr( "scrollTop", 0 );
		}

		$( "#city" ).autocomplete({
			source: "search_city.php",
			minLength: 2
			/*select: function( event, ui ) {
				log( ui.item ?
					"Selected: " + ui.item.value + " aka " + ui.item.id :
					"Nothing selected, input was " + this.value );
			}*/
		});

			$('#swfupload-control').css('visibility', 'hidden');
	    });
		


	    function pesquisar(){
		$.getJSON('search.php', {term: encodeURI($('#term').val()), city: encodeURI($('#city').val())},
		    function(data){
			$('#poi_list').attr('innerHTML','');
			if(data.length <= 0){
			    alert('Nenhum local encontrado. Refine sua busca.');
			}else{
			    $('#msgPesquisa').attr('innerHTML','<h3><strong>2. Selecione um dos locais abaixo:</strong></h3>');
			    for(x=0; x < data.length; x++){
				var appendText = '<li><a href="javascript:void(0);" onclick="setLbsId(\'' + data[x]['lbsid'] + '\', \'' + data[x]['link'] + '\',this);">' + data[x]['nome'] + ' - ' + data[x]['endereco'] + '</a>';
				appendText = appendText + ' <a href="' + data[x]['link'] + '" target="_blank"><img src="img/external_link_graphic.png" title="ir para o site" alt="ir para o site" border="0"/></a></li>';
				$('#poi_list').append(appendText);
			    }
			}
		    }, "json");
	    }

	    function setLbsId(lbsid, link, objLink){
			$('#poi_list a').removeClass('selected');
			$('#lbsid').val(lbsid);
			objLink.className = 'selected';		
			var url = "<?php echo APP_URL?>upload-file.php?oauth_token=<?php echo $_COOKIE['oauth_token']?>&oauth_token_secret=<?php echo $_COOKIE['oauth_token_secret']?>&lbsid=" + lbsid + "&user_id=<?php echo $_COOKIE['user_id']?>";
			$('#swfupload-control').css('visibility', '');
			//$('#swfupload-control').show(0, function(){
			    $.swfupload.getInstance($('#swfupload-control')).setUploadURL(url);
			//});
		    url_poi = link;
	    }

//Region Trim stuff
		function trim(str, chars) {
			return ltrim(rtrim(str, chars), chars);
		}
		 
		function ltrim(str, chars) {
			chars = chars || "\\s";
			return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
		}
		 
		function rtrim(str, chars) {
			chars = chars || "\\s";
			return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
		}
//EndRegion Trim stuff
	    
		function searchByURLorLBSID(urlstring){
			urlstring = trim(urlstring);
			//primeiro a busca por lbsid
			var reg = new RegExp(/^([a-z0-9]+)$/i);
			var match = reg.exec(urlstring);
			if(match){
				searchAndSetLbsId(match[1]);
				return;
			}
			
			var reg = new RegExp(/\/([a-z0-9]+)\/[0-9a-z_]+\.html/i);
			var match = reg.exec(urlstring);
			if(match){
				var _lbsid = match[1];
				searchAndSetLbsId(_lbsid);
			}else{
				alert('Não foi possível extrair o LbsId do URL ou texto digitado.');
			}
		}
	    
	    function searchAndSetLbsId(lbsid){
//	    	$('#lbsid').val(lbsid);
			//faz a busca
	    	$.getJSON('search.php', {lbsid: lbsid},
	    		    function(data){
		    			$('#poi_list').attr('innerHTML','');
		    			if(data.length <= 0){
		    			    alert('Nenhum local encontrado com esse lbsid. Refine sua busca.');
		    			}else{
			    			var strhtml = "<h3><strong>2. Envie at&eacute; 50 arquivos (jpg, png, gif), com no m&aacute;ximo 1MB cada.</strong></h3>";
			    			strhtml += ">Selecione um dos locais abaixo:";
		    			    $('#msgPesquisa').attr('innerHTML', strhtml);		    			    
		    			    for(x=0; x < data.length; x++){
			    				var appendText = '<li><a href="javascript:void(0);" id="lbsidzero" onclick="setLbsId(\'' + data[x]['lbsid'] + '\', \'' + data[x]['link'] + '\',this);">' + data[x]['nome'] + ' - ' + data[x]['endereco'] + '</a>';
			    				appendText = appendText + ' <a href="' + data[x]['link'] + '" target="_blank"><img src="img/external_link_graphic.png" title="ir para o site" alt="ir para o site" border="0"/></a></li>';
			    				$('#poi_list').append(appendText);
		    			    }
		    			}
		    			if(data.length == 1){
							setLbsId(data[0]['lbsid'], data[0]['link'], $('#lbsidzero'));
							$('#lbsidzero').attr('className','selected');
		    			}
	    		    }, "json");
	    }
	    <?php 
	    if(isset($_GET['lbsid'])){
	    	echo sprintf("searchAndSetLbsId('%s');", $_GET['lbsid']);
	    }
	    ?>
	</script>


	</head>
	<body onload="$('#term').focus();">
		<div class="wrapper">
			<img src="img/logo.png" class="logo" />
			<div class="contentwrapper">
				<h2>Envie v&aacute;rias fotos para o Apontador de uma s&oacute; vez.</h2>

				<form onSubmit="pesquisar(); return false;">
					<div id="buscar">
						<h3><strong>1. Encontre o local onde você tirou as fotos:</strong></h3>
						<label for="term">O qu&ecirc;? </label><input type="text" name="term" id="term"/>
						<label for="term">Onde? </label><input type="text" name="city" id="city"/>
						<!--		    <input type="button" id="pesquisar" name="pesquisar" value="pesquisar" onclick="pesquisar();"/>-->
						<input type="submit" id="vai" class="vai" name="vai" value="Encontrar"/>
						<hr noshade size="1"/>
						<label for="urlpoisearch">Ou digite a URL ou LbsID:</label><input type="text" id="urlpoisearch" size="30"/> 
						<input type="button" onclick="searchByURLorLBSID($('#urlpoisearch').val());" value="Encontrar" class="vai" id="urlpoisearchbutton"/>
					</div>
				</form>
				<script type="text/javascript">
					$('#vai').button();
					$('#urlpoisearchbutton').button();
				</script>

				<div id="msgPesquisa"></div>
				<ul id="poi_list"></ul>


				<div id="swfupload-control">
						<input type="hidden" id="lbsid" />
						<input type="button" id="button" />
						<p id="queuestatus" ></p>
						<ol id="log"></ol>
					</div>
				</div>
			</div>
	<hr />
	<div class="footer">
		<!-- INICIO FORMULARIO BOTAO PAGSEGURO -->
		<form target="pagseguro" action="https://pagseguro.uol.com.br/checkout/doacao.jhtml" method="post">
		<input type="hidden" name="email_cobranca" value="ricardo@ricardomartins.info" />
		<input type="hidden" name="moeda" value="BRL" />
		<input type="image"  id="doar" src="https://p.simg.uol.com.br/out/pagseguro/i/botoes/doacoes/84x35-doar-cinza.gif" name="submit" alt="=)" title="=)" />
		</form>
		<!-- FINAL FORMULARIO BOTAO PAGSEGURO -->	
	</div>
	
	
	
	<?php
	//adiciona o usuario na tb upload_foto a fim de sabermos qtas fotos ele enviou usando o upload_multiplo 
	require_once 'classes/ApontadorApiLib.php';
	$usr_info = apontadorChamaApi("GET", "users/self", array("type"=>"json"), $_COOKIE['oauth_token'], $_COOKIE['oauth_token_secret']);
	
	$usr_info = json_decode($usr_info);
	$usr_info = $usr_info->user;
	if(!isset($_COOKIE['user_id'])){
		setcookie('user_id',$usr_info->id, time()+31536000, '/');
	}
	try {
    	$db = new PDO($dsn, $usr, $pwd);
    	$qtd_fotos = 0;
		//@TODO: Fazer direito
    	foreach($db->query("SELECT qtd_fotos FROM upload_foto WHERE id = " . $usr_info->id) as $qtd){
    		$qtd_fotos = $qtd['qtd_fotos'];
    	}
    
    	$db->exec(sprintf("REPLACE INTO upload_foto (id,nome,photo_url,ultima_atividade,qtd_fotos) values(0%s,'%s','%s',now(),%s)",$usr_info->id, $usr_info->name, $usr_info->photo_url, $qtd_fotos));
	} catch (PDOException $e) {
//	    echo 'Connection failed: ' . $e->getMessage();
	}
	
	if(isset($_GET['lbsid'])){
		echo sprintf("\n<script>searchAndSetLbsId('%s');</script>\n", $_GET['lbsid']);
	}
	?>
	
	<!-- Analytics -->
	<script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-3314217-4']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	</script>
    </body>
</html>