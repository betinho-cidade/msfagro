
<html lang="en"><head>

    <meta charset="UTF-8">

  <link rel="apple-touch-icon" type="image/png" href="https://cpwebassets.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png">
  <meta name="apple-mobile-web-app-title" content="CodePen">

  <link rel="shortcut icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico">

  <link rel="mask-icon" type="" href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-8f3771b1072e3c38bd662872f6b673a722f4b3ca2421637d5596661b4e2132cc.svg" color="#111">


    <title>Movimentações no Período</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flexboxgrid/6.3.1/flexboxgrid.min.css">

  <style>
  .serif{
      font-family: "Poppins",Arial,Helvetica,sans-serif;
  }
  .texto {
  margin: 0;
  }
  .negrito {
  font-weight: 700
  }
  .negrito-2 {
  font-weight: 600
  }
  .sublinhar{
  text-decoration: underline;
  }
  .center {
  text-align: center
  }
  .esquerda {
  text-align: right;
  }
  .overline {
  text-decoration: overline;
  }
  .quebra_linha{
  display: block;
  }
  /*configurações de fonts*/
  .font-17 {
  font-size: 17px;
  }
  .font-24 {
  font-size: 24px;
  }
  .font-36 {
  font-size: 36px;
  }
  .font-40 {
  font-size: 40px;
  }
  /*confifurações de margin*/
  .padding_top_35{
  padding-top:35px;
  }
  .margin_bottom_35{
  margin-bottom: 35px;
  }
  .altura_linhas_19{
  line-height: 19px;
  }
  .altura_linhas_35{
  line-height: 35px;
  }
  .altura_linhas_25{
  line-height: 25px;
  }
  .caixa_informacoes_aluno_cea{
  font-size: 20px;
  text-align: justify;
  padding-right: 0px;
  margin-right: -40px;
  }
  .caixa_informacoes_aluno_cea_2{
  font-size: 22px;
  text-align: justify;
  padding-right: 46px;
  padding-left: 46px;
  }
  .caixa_informacoes_aluno_cea p{
  margin-top: 5px;
  }
  .assinatura_cea{
  line-height: 22px;
  font-size: 22px;
  padding-top: 22px;
  }
  .titulo_cea_2{
  margin-top: 35px
  margin-bottom: 35px
  }
  .data_entrega_cea{
  padding-top: 25px;
  padding-bottom: 43px;
  }
  /*configurações de exibição da pagina e conteudo*/
  .end-xs {
    width: 100%;
    margin-left: 15%;
    -webkit-box-pack: initial;
    -ms-flex-pack: initial;
    justify-content: initial;
    text-align: initial;
  }
  .certificado_conteudo {
  -webkit-print-color-adjust: exact;
  background-image: url(http://cityinbag.com/famali/wp-content/uploads/2021/04/fundo-certificado2.jpg);
  height: 755px;
  width: 1085px;
  background-repeat: no-repeat
  }
  .certificado_pagina {
  padding: 0;
  width: 1085px;
  margin: initial;
  box-shadow: .5px .5px 7px #000;
  border-radius: 2px;
  overflow: hidden;
  }
  /*area de configuraçõa da pagina*/
  @page {
  size: 297mm 210mm;
  margin: 5mm;
  size: landscape
  }
  body {
  margin: 0;
  padding: 0px !important;
      font-family: "Poppins",Arial,Helvetica,sans-serif;}
  p{
  margin: 0px;
  }
  /*SEMPRE DEIXAR NO FIM DO CODIGO configuração de impresão*/
  @media print {
  .certificado_pagina {
  padding: 0;
  background: transparent;
  margin: 0;
  border-radius: 0;
  box-shadow: none;
  -webkit-box-shadow: none
  }
  }
  </style>

    <script>
    window.console = window.console || function(t) {};
  </script>



    <script>
    if (document.location.search.match(/type=embed/gi)) {
      window.parent.postMessage("resize", "*");
    }
  </script>


  </head>

  <body translate="no">

    <div class="tab-pane active" id="lancamento" role="tabpanel">
                    <table id="dt_lancamentos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr style="background-color:#CECECE">
                            <th style="align:center">Data</th>
                            <th style="align:center">Tipo</th>
                            <th style="align:center">Valor</th>
                            <th style="align:center">Item</th>
                            <th style="align:center">Empresa</th>
                            <th style="align:center">Forma Pagamento</th>
                            <th style="align:center">Produtor</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($movimentacaos as $movimentacao)
                        <tr style="background-color:{{($movimentacao->tipo == 'D') ? '#FF7171' : '#73FD76'}}">
                            <td style="text-align:center;font-size:12px;">{{$movimentacao->data_programada_formatada}}</td>
                            <td style="text-align:center;font-size:12px;">{{$movimentacao->tipo_movimentacao_texto}}</td>                            
                            <td style="text-align:center;font-size:12px;">R$ {{number_format($movimentacao->valor, 2, ',', '.')}}</td>                            
                            <td style="text-align:center;font-size:12px;">{{$movimentacao->item_texto}}</td>
                            <td style="text-align:center;font-size:12px;">{{$movimentacao->empresa->nome_empresa ?? '...'}}</td>
                            <td style="text-align:center;font-size:12px;">{{ $movimentacao->forma_pagamento->forma ?? '...'}}</td>
                            <td style="text-align:center;font-size:12px;">{{ $movimentacao->produtor->nome_produtor ?? '...' }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA lancamento - ATIVA - FIM -->
    </div>                  
    
    <br><br>

    <div class="tab-pane active" id="lancamento" role="tabpanel">
                    <table id="dt_lancamentos" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr style="background-color:#CECECE">
                            <th style="align:center">Receitas</th>
                            <th style="align:center">Despesas</th>
                            <th style="align:center">Resultado Final</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td style="text-align:center;color: #548235">R$ {{number_format($resultado_final['receita'], 2, ',', '.')}}</td>
                            <td style="text-align:center;color: #FF0000">R$ {{number_format($resultado_final['despesa'], 2, ',', '.')}}</td>
                            <td style="text-align:center;color: {{($resultado_final['saldo'] == 'P') ? '#548235' : '#FF0000'}}">R$ {{number_format($resultado_final['total'], 2, ',', '.')}}</td>                            
                        </tr>
                        </tbody>
                    </table>
                    <!-- Nav tabs - LISTA lancamento - ATIVA - FIM -->
    </div>   

  </body></html>
