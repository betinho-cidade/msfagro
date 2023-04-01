<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8"> <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"> <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting">  <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title></title> <!-- The title tag shows in email notifications, like Android 4.4. -->

    <link href="https://fonts.googleapis.com/css?family=Work+Sans:200,300,400,500,600,700" rel="stylesheet">

    <!-- CSS Reset : BEGIN -->
    <style>

        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
body {
    margin: 0 auto !important;
    padding: 0 !important;
    height: 100% !important;
    width: 100% !important;
    background: #f1f1f1;
}

/* What it does: Stops email clients resizing small text. */
* {
    -ms-text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%;
}

/* What it does: Centers email on Android 4.4 */
div[style*="margin: 16px 0"] {
    margin: 0 !important;
}

/* What it does: Stops Outlook from adding extra spacing to tables. */
table,
td {
    mso-table-lspace: 0pt !important;
    mso-table-rspace: 0pt !important;
}

/* What it does: Fixes webkit padding issue. */
table {
    border-spacing: 0 !important;
    border-collapse: collapse !important;
    table-layout: fixed !important;
    margin: 0 auto !important;
}

/* What it does: Uses a better rendering method when resizing images in IE. */
img {
    -ms-interpolation-mode:bicubic;
}

/* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
a {
    text-decoration: none;
}

/* What it does: A work-around for email clients meddling in triggered links. */
*[x-apple-data-detectors],  /* iOS */
.unstyle-auto-detected-links *,
.aBn {
    border-bottom: 0 !important;
    cursor: default !important;
    color: inherit !important;
    text-decoration: none !important;
    font-size: inherit !important;
    font-family: inherit !important;
    font-weight: inherit !important;
    line-height: inherit !important;
}

/* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
.a6S {
    display: none !important;
    opacity: 0.01 !important;
}

/* What it does: Prevents Gmail from changing the text color in conversation threads. */
.im {
    color: inherit !important;
}

/* If the above doesn't work, add a .g-img class to any image in question. */
img.g-img + div {
    display: none !important;
}

/* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
/* Create one of these media queries for each additional viewport size you'd like to fix */

/* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
@media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
    u ~ div .email-container {
        min-width: 320px !important;
    }
}
/* iPhone 6, 6S, 7, 8, and X */
@media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
    u ~ div .email-container {
        min-width: 375px !important;
    }
}
/* iPhone 6+, 7+, and 8+ */
@media only screen and (min-device-width: 414px) {
    u ~ div .email-container {
        min-width: 414px !important;
    }
}

    </style>

    <!-- CSS Reset : END -->

    <!-- Progressive Enhancements : BEGIN -->
    <style>

	    .primary{
	background: #ff8b00;
}
.bg_white{
	background: #ffffff;
}
.bg_light{
	background: #fafafa;
}
.bg_black{
	background: #000000;
}
.bg_dark{
	background: rgba(0,0,0,.8);
}
.email-section{
	padding:2.5em;
}

/*BUTTON*/
.btn{
	padding: 5px 20px;
	display: inline-block;
}
.btn.btn-primary{
	border-radius: 5px;
	background: #ff8b00;
	color: #ffffff;
}
.btn.btn-white{
	border-radius: 5px;
	background: #ffffff;
	color: #000000;
}
.btn.btn-white-outline{
	border-radius: 5px;
	background: transparent;
	border: 1px solid #fff;
	color: #fff;
}
.btn.btn-black{
	border-radius: 0px;
	background: #000;
	color: #fff;
}
.btn.btn-black-outline{
	border-radius: 0px;
	background: transparent;
	border: 2px solid #000;
	color: #000;
	font-weight: 700;
}
.btn.btn-custom{
	text-transform: uppercase;
	font-weight: 600;
	font-size: 12px;
}

h1,h2,h3,h4,h5,h6{
	font-family: 'Work Sans', sans-serif;
	color: #000000;
	margin-top: 0;
	font-weight: 400;
}

body{
	font-family: 'Work Sans', sans-serif;
	font-weight: 400;
	font-size: 15px;
	line-height: 1.8;
	color: rgba(0,0,0,.5);
}

a{
	color: #ff8b00;
}

p{
	margin-top: 0;
}

table{
}
/*LOGO*/

.logo h1{
	margin: 0;
}
.logo h1 a{
	color: #000000;
	font-size: 20px;
	font-weight: 700;
	/*text-transform: uppercase;*/
	font-family: 'Work Sans', sans-serif;
}

.navigation{
	padding: 0;
	padding: 1em 0;
	/*background: rgba(0,0,0,1);*/
	border-top: 1px solid rgba(0,0,0,.05);
	border-bottom: 1px solid rgba(0,0,0,.05);
	margin-bottom: 0;
}
.navigation li{
	list-style: none;
	display: inline-block;;
	margin-left: 5px;
	margin-right: 5px;
	font-size: 14px;
	font-weight: 500;
}
.navigation li a{
	color: rgba(0,0,0,1);
}

/*HERO*/
.hero{
	position: relative;
	z-index: 0;
}
.hero .overlay{
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	content: '';
	width: 100%;
	background: #000000;
	z-index: -1;
	opacity: .2;
}
.hero .text{
	color: rgba(255,255,255,.9);
	max-width: 50%;
	margin: 0 auto 0;
}
.hero .text h2{
	color: #fff;
	font-size: 34px;
	margin-bottom: 0;
	font-weight: 400;
	line-height: 1.4;
}
.hero .text h2 span{
	font-weight: 600;
	color: #ff8b00;
}

/*INTRO*/
.intro{
	position: relative;
	z-index: 0;
}

.intro .text{
	color: rgba(0,0,0,.3);
}
.intro .text h2{
	color: #000;
	font-size: 34px;
	margin-bottom: 0;
	font-weight: 300;
}
.intro .text h2 span{
	font-weight: 600;
	color: #ff8b00;
}

/*SERVICES*/
.services{
}
.text-services{
	padding: 10px 10px 0; 
	text-align: center;
}
.text-services h3{
	font-size: 16px;
	font-weight: 500;
}

.services-list{
	margin: 0 0 20px 0;
	width: 100%;
}

.services-list img{
	float: left;
}
.services-list h3{
	margin-top: 0;
	margin-bottom: 0;
}
.services-list p{
	margin: 0;
	font-size:12px;
}



/*COUNTER*/
.counter{
	width: 100%;
	position: relative;
	z-index: 0;
}
.counter .overlay{
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	content: '';
	width: 100%;
	background: #000000;
	z-index: -1;
	opacity: .3;
}
.counter-text{
	text-align: center;
}
.counter-text .num{
	display: block;
	color: #ffffff;
	font-size: 34px;
	font-weight: 700;
}
.counter-text .name{
	display: block;
	color: rgba(255,255,255,.9);
	font-size: 13px;
}

/*TOPIC*/
.topic{
	width: 100%;
	display: block;
	float: left;
	border-bottom: 1px solid rgba(0,0,0,.1);
	padding: 1.5em 0;
}
.topic .img{
	width: 120px;
	float: left;
}
.topic .text{
	width: calc(100% - 150px);
	padding-left: 20px;
	float: left;
}
.topic .text h3{
	font-size: 20px;
	margin-bottom: 15px;
	line-height: 1.2;
}
.topic .text .meta{
	margin-bottom: 10px;
}

/*HEADING SECTION*/
.heading-section{
}
.heading-section h2{
	color: #000000;
	font-size: 28px;
	margin-top: 0;
	line-height: 1.4;
	font-weight: 400;
}
.heading-section .subheading{
	margin-bottom: 20px !important;
	display: inline-block;
	font-size: 13px;
	text-transform: uppercase;
	letter-spacing: 2px;
	color: rgba(0,0,0,.4);
	position: relative;
}
.heading-section .subheading::after{
	position: absolute;
	left: 0;
	right: 0;
	bottom: -10px;
	content: '';
	width: 100%;
	height: 2px;
	background: #fff;
	margin: 0 auto;
}

.heading-section-white{
	color: rgba(255,255,255,.8);
}
.heading-section-white h2{
	font-family: 
	line-height: 1;
	padding-bottom: 0;
}
.heading-section-white h2{
	color: #ffffff;
}
.heading-section-white .subheading{
	margin-bottom: 0;
	display: inline-block;
	font-size: 13px;
	text-transform: uppercase;
	letter-spacing: 2px;
	color: #fff;
}


ul.social{
	padding: 0;
}
ul.social li{
	display: inline-block;
	margin-right: 10px;
	/*border: 1px solid #ff8b00;*/
	padding: 10px;
	border-radius: 50%;
	background: rgba(0,0,0,.05);
}

/*FOOTER*/

.footer{
	border-top: 1px solid rgba(0,0,0,.05);
	color: rgba(0,0,0,.5);
}
.footer .heading{
	color: #000;
	font-size: 20px;
}
.footer ul{
	margin: 0;
	padding: 0;
}
.footer ul li{
	list-style: none;
	margin-bottom: 10px;
}
.footer ul li a{
	color: rgba(0,0,0,1);
}


@media screen and (max-width: 500px) {


}


    </style>


</head>

<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #fafafa;">
	<center style="width: 100%; background-color: #f1f1f1;">
    <div style="display: none; font-size: 1px;max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
      &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
    </div>
    <div style="max-width: 600px; margin: 0 auto;" class="email-container">
    	<!-- BEGIN BODY -->
      <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
      	<tr style="background: #fff;">
          <td valign="top" class="bg_light" style="padding: .5em 2.5em 1em 2.5em;">
          	<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
          		<tr>
          			<td class="logo" style="text-align: center;">
                        <img src="{{asset('images/logo.png')}}" alt="" style="max-width: 150px; height: auto; margin: auto; display: block;">
			          </td>
          		</tr>
          	</table>
          </td>
	      </tr><!-- end tr -->
	      <tr>
          <td valign="middle" class="hero bg_white" style="background-image: url({{asset('images/Grupo10.png')}}); background-size: cover; height: 400px;">
          	<div class="overlay"></div>
            <table>
            	<tr>
            		<td>
            			<div class="text" style="padding: 0 4em; text-align: center;">
						    <p style="color: #fff;font-size: 19px;">Olá</p>
            				<h2 style="line-height: 30px;color: #fff;font-size: 34px;margin-bottom: 0;font-weight: 400;margin-top: 10px;">{{$filiado->user->name}}</h2>
            			</div>
            		</td>
            	</tr>
            </table>
          </td>
	      </tr><!-- end tr -->
	      <tr>
          <td class="bg_dark email-section" style="text-align:center;background:#005FAA;padding: 2.5em;">
          	<div class="heading-section heading-section-white">
          		<span class="subheading" style="    display: inline-block;font-size: 13px;text-transform: uppercase;letter-spacing: 2px;color: #fff;border-bottom: 1px solid #fff;">BEM VINDO</span>
            	<h2 style="font-size: 28px;color:#fff;line-height: 1.4;font-weight: 400;">AAPOMIL</h2>
            	<p style="line-height: 22px;font-size: 15px;color: #fff;">Sua filiação foi processada e agora você faz parte do Quadro Social da Associação de Apoio aos Policiais Militares de Londrina e Região. O principal objetivo da AAPOMIL é auxiliá-lo nos momentos de vulnerabilidade através dos recursos do FUNPOMIL – Fundo Pecuniário de Apoio aos Policiais Militares de Londrina e Região. Seguem algumas informações importantes:</p>
          	</div>
          </td>
        </tr><!-- end: tr -->
		<tr>
	        <td class="bg_white email-section" style="width: 100%;padding: 2.5em;background: #fff;">
	        	<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
	        		<tr>
	        			<td valign="middle" width="50%">
	                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
	                  <tr>
	                    <td class="text-services" style="text-align: left; padding-right:25px;">
					            	<div class="services-list">
					            		<div class="text">
											<p style="color: #777;">Nome</p>
					            			<h3 style="font-size: 16px;font-weight: 500;text-transform: uppercase;margin: 0;">{{$filiado->user->name}}</h3>
					            		</div>
					            	</div>
					            	<div class="services-list">
					            		<div class="text">
											<p style="color: #777;">Categoria</p>
					            			<h3 style="font-size: 16px;font-weight: 500;text-transform: uppercase;margin: 0;">{{$filiado->categoria->nome}}</h3>
					            		</div>
					            	</div>
					            	<div class="services-list">
					            		<div class="text">
											<p style="color: #777;">Data de Filiação</p>
					            			<h3 style="font-size: 16px;font-weight: 500;text-transform: uppercase;margin: 0;">{{$filiado->adm_data_admissao}}</h3>
					            		</div>
					            	</div>
					            	<div class="services-list">
					            		<div class="text">
											<p style="color: #777;">Carência</p>
					            			<h3>{{$filiado->admissao()->adm_carencia}}</h3>
					            		</div>
					            	</div>
					            	<div class="services-list">
					            		<div class="text">
											<p style="color: #777;">Data de termino da carência</p>
					            			<h3 style="font-size: 16px;font-weight: 500;text-transform: uppercase;margin: 0;">{{$filiado->adm_data_termino_carencia}}</h3>
					            		</div>
					            	</div>							
	                    </td>
	                  </tr>
	                </table>
	              </td>
	        			<td valign="middle" width="50%">
	                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
	                  <tr>
	                    <td>
	                      <img src="{{asset('images/Grupo_de_mascara2.png')}}" alt="" style="width: 100%; max-width: 600px; height: auto; margin: auto; display: block;">
	                    </td>
	                  </tr>
	                </table>
	              </td>
	            </tr>
	        	</table>
	        </td>
	      </tr><!-- end: tr -->
		  <tr>
          <td class="bg_dark email-section" style="text-align:center;background:#005FAA;padding: 2.5em;">
          	<div class="heading-section heading-section-white" style="color:#fff;">
          		<span class="subheading" style="    display: inline-block;font-size: 13px;text-transform: uppercase;letter-spacing: 2px;color: #fff;border-bottom: 1px solid #fff;margin-bottom: 10px;">DADOS FINANCEIROS</span>
            	<p style="line-height: 16px;text-align:left; margin-top:10px;font-size: 15px;">TAXA DE CONTRIBUIÇÃO (INTEGRAL/PARCIAL/MÍNIMA): R$ ({{$filiado->categoria_valor}})</p>
			  	<p style="line-height: 16px;text-align:left;font-size: 15px;">FORMA DE PAGAMENTO: {{$filiado->adm_forma_pagamento_escolhida}}</p>
            	<p style="line-height: 16px;text-align:left;font-size: 15px;">NA OPÇÃO BOLETO: Você receberá os boletos para pagamento mensalmente, através do e-mail cadastrado, com vencimento para o dia 05.</p>
            	<p style="line-height: 16px;text-align:left;font-size: 15px;">NA OPÇÃO DÉBITO DIRETO AUTORIZADO: A sua Taxa de Contribuição será debitada automaticamente no 1º dia útil de cada mês, mas para isso, você precisará autorizar o desconto. Quando a autorização for disponibilizada, a associação entrará em contato através do e-mail ou telefone cadastrado. Você pode conferir o passo-a-passo para autorização do débito <a href="https://aapomil.com.br/wp-content/uploads/2020/09/passo-a-passo-debito.pdf" target="_blank">clicando aqui.</a></p>
			  	<p style="line-height: 16px;text-align:left;margin-bottom:30px;font-size: 15px;">Esse é o valor da sua primeira Taxa de Contribuição.<br/>Será debitada no dia <b>*{{$filiado->adm_data_primeiro_pagamento}}*</b></p>
			  	<p style="line-height: 16px;text-align:center;font-size: 15px;">Taxa de Admissão: <b>R$ {{$filiado->adm_taxa}}</b></p>
				<p style="line-height: 16px;text-align:center;font-size: 15px;">Taxa de Contribuição: <b>R$ {{$filiado->categoria_valor_total}}</b></p>
				<p style="line-height: 16px;text-align:center;font-size: 15px;">TOTAL DO PRIMEIRO PAGAMENTO: <b>R$ {{$filiado->adm_total_admissao}}</b></p>

				
          	</div>
          </td>
        </tr><!-- end: tr -->
	      <tr>
          <td class="bg_white email-section" style="padding: 2.5em;background: #fff;">
          	<div class="heading-section" style="text-align: center; padding: 0 30px;">
            	<h2 style="margin-bottom:10px;color: #000000;font-size: 28px;margin-top: 0;line-height: 1.4;font-weight: 400;">CAA</h2>
				<h3 style="margin-bottom: 0px;color: #000000;margin-top: 0;font-size: 17px;font-weight: 400;">Central de Atendimento ao Associado</h3>
            	<p style="line-height: 16px;font-size: 14px;margin-top: 3px;color: #848484;">Para esclarecimentos, dúvidas, sugestões e críticas o Associado poderá entrar em contato com entidade através dos canais de atendimento da CAA.</p>
          	</div>
          	<table role="presentation" border="0" cellpadding="10" cellspacing="0" width="100%">
          		<tr>
		            <td valign="top" style="width: 100%;">
		            	<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
		            		<tr>
		            			<td valign="top" width="100%">
		            				<div class="topic" style="margin-bottom: 40px;">
			            				<div class="img">
				            				<img src="https://aapomil.com.br/wp-content/uploads/2020/09/grupo4.png" alt="" style="width: auto;max-width: 600px;height: auto;margin: auto;margin-bottom: 20px;display: block;float: left;margin-right: 20px;">
			            				</div>
			            				<div class="text">
			            					<p class="meta"><span style="font-size: 16px;color: #ababab;">email</span></p>
			            					<h3 style="font-size: 21px;margin-top: -5px;margin-bottom: -5px;">caa@aapomil.com.br</h3>
			            					<p style="font-size: 14px;line-height: 14px;margin-top: 20px;color: #9c9c9c;">Entre em contato através do nosso e-mail oficial de suporte, canal exclusivo para já associados.</p>
			            				</div>
		            				</div>
		            				<div class="topic" style="border-bottom: none;">
			            				<div class="img">
				            				<img src="https://aapomil.com.br/wp-content/uploads/2020/09/grupo5.png" alt="" style="width: auto;max-width: 600px;height: auto;margin: auto;margin-bottom: 20px;display: block;float: left;margin-right: 20px;">
			            				</div>
			            				<div class="text">
			            					<p class="meta"><span style="font-size: 16px;color: #ababab;">WhatsApp</span></p>
			            					<h3 style="font-size: 21px;margin-top: -5px;margin-bottom: -5px;">43 998853-7622</h3>
			            					<p style="font-size: 14px;line-height: 14px;margin-top: 20px;color: #9c9c9c;">Estamos também no WhatsApp para proporcional um atendimento mais ágil ao associado, canal exclusivo para já associados.</p>
			            				</div>
		            				</div>

		            			</td>
                    </tr>
		            	</table>
		            </td>
		          </tr><!-- end: tr -->	  
          	</table>
          </td>
        </tr><!-- end: tr -->
			      <tr>
          <td class="bg_dark email-section" style="text-align:center;background:#005FAA;padding: 2.5em;">
          	<div class="heading-section heading-section-white">
          		<span class="subheading"style="display: inline-block;font-size: 13px;text-transform: uppercase;letter-spacing: 2px;color: #fff;border-bottom: 1px solid #fff;margin-bottom: 10px;">SOLICITAÇÕES</span>
            	<p style="line-height:16px;margin-top: 10px;color: #fff;">Para a realização de solicitações formais, como Liberação de Recursos do FUNPOMIL, desligamento do Quadro Social, consultas jurídicas formalizadas, etc., o associado deverá entrar na Área Restrita do site <a href="color: #fff;text-decoration: underline;">www.aapomil.com.br</a> e acessar o Protocolo Online.</p>
          	</div>
          </td>
        </tr><!-- end: tr -->
	      <tr>
          <td class="bg_light email-section" style="padding: 2.5em; background:#fff;">
          	<div class="heading-section" style="text-align: center; padding: 0 30px;">
            	<h2 style=" margin-bottom: 0;margin-top: 0;font-size: 25px;">Redes Sociais</h2>
            	<p style=" margin-bottom: 40px;font-size: 15px;margin-top: 10px;">Nos acompanhe nas nossas redes sociais.</p>
          	</div>
          	<table role="presentation" border="0" cellpadding="10" cellspacing="0" width="100%">
          		<tr>
                <td valign="top" width="33.333%" style="padding-top: 20px;">
                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                      <td>
                        <a href="https://pt-br.facebook.com/aapomil/"><img src="{{asset('images/Grupo6.png')}}" alt="" style="width: 80px; max-width: 600px; height: auto; margin: auto; margin-bottom: 20px; display: block; border-radius: 50%;"></a>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-testimony" style="text-align: center;">
                      	<span class="position">FACEBOOK</span>
                      </td>
                    </tr>
                  </table>
                </td>
                <td valign="top" width="33.333%" style="padding-top: 20px;">
                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                      <td>
                        <a href="https://www.instagram.com/aapomil/?hl=pt-br"><img src="{{asset('images/Grupo8.png')}}" alt="" style="width: 80px; max-width: 600px; height: auto; margin: auto; margin-bottom: 20px; display: block; border-radius: 50%;"></a>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-testimony" style="text-align: center;">
                      	<span class="position">INSTAGRAM</span>
                      </td>
                    </tr>
                  </table>
                </td>
                <td valign="top" width="33.333%" style="padding-top: 20px;">
                  <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                      <td>
                        <a href="https://www.youtube.com/channel/UCEG_5mukn6XLNsHIy-pI2WQ?app=desktop"><img src="{{asset('images/Grupo9.png')}}" alt="" style="width: 80px; max-width: 600px; height: auto; margin: auto; margin-bottom: 20px; display: block; border-radius: 50%;"></a>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-testimony" style="text-align: center;">
                      	<span class="position">YOUTUBE</span>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
          	</table>
          </td>
        </tr><!-- end: tr -->
      <!-- 1 Column Text + Button : END -->
      </table>
      <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">

        <tr>
          <td class="bg_white" style="text-align: center; padding-top: 20px;background: #ffffff;">
          	<p>Todos os direitos reservados AAPOMIL</p>
          </td>
        </tr>
      </table>

    </div>
  </center>
</body>
</html>


