@extends('painel.layout.index')

@section('content')

@if ($errors->any())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
        </div>
    </div>
@endif

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Geolocalização das Fazendas</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div id="map"></div>

@endsection

@section('head-css')

<style>
  #map {
      height: 400px;
      width: 100%;
  }
</style>

@endsection


@section('script-js')
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

<script>
  (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
    key: '{{env('API_KEY_GOOGLE')}}',
    // Add other bootstrap parameters as needed, using camel case.
    // Use the 'v' parameter to indicate the version to load (alpha, beta, weekly, etc.)
  });
</script>

<script>
  const parser = new DOMParser();

  async function initMap() {
    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");
    const map = new Map(document.getElementById("map"), {
      center: { lat: -8.23121, lng: -55.53612  }, // centro do Brasil
      zoom: 3,
      mapId: "Brasil-MFSAgro",
    });

    @foreach($fazendas as $fazenda)

      const contentString_{{$fazenda->id}} =
        '<div id="content">' +
        '<div id="siteNotice">' +
        "</div>" +
        '<h4 id="firstHeading" class="firstHeading">{{$fazenda->nome_fazenda}}</h4>' +
        @if($user->cliente_user->cliente->tipo != 'AG')
        '<div id="bodyContent">' +
        "<p><b>Quantidade de Machos:</b> {{$fazenda->qtd_macho}}</p>" +
        "<p><b>Quantidade de Fêmeas:</b> {{$fazenda->qtd_femea}}</p>" +
        "</div>" +
        @endif
        "</div>";
      const infowindow_{{$fazenda->id}} = new google.maps.InfoWindow({
        content: contentString_{{$fazenda->id}},
        ariaLabel: "Uluru",
      });

      const marker_{{$fazenda->id}} = new google.maps.Marker({
        position: { 
          lat: {{$fazenda->latitude}}, 
          lng: {{$fazenda->longitude}}  
        },
        map,
        title: "{{$fazenda->nome_fazenda}}",
      });

      marker_{{$fazenda->id}}.addListener("click", () => {
        infowindow_{{$fazenda->id}}.open({
          anchor: marker_{{$fazenda->id}},
          map,
        });
      });  

    @endforeach
  }

  initMap();
</script>

@endsection


