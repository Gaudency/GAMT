<div class="main-banner">
    <div class="container">
      <div class="row">

        <!-- ***** Main Banner Area Start ***** -->
   @if(session()->has('message'))

            <div class="alert alert-success">

             

            {{session()->get('message')}}

             <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">x</button>

             </div>

            @endif
        <div class="col-lg-6 align-self-center">
          <div class="header-text">
            <h6>Proyectos Del Municipio</h6>
            <h2>Respaldos del GAMT</h2>
            <p>Este proyecto esta siendo desarrollado por un junior de la carrera de ingenieria informatica de la UATF de latino America Bolivia </p>
            <div class="buttons">
              <div class="border-button">
                <a href="explore.html">Explore Top Carpetas</a>
              </div>
              <div class="main-button">
                <a href="https://www.instagram.com/gamtomave" target="_blank">Instagram</a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5 offset-lg-1">
          <div class="">
            <div class="item">
              <img src="assets/images/banner.png" alt="">
            </div>
            <div class="item">
              <img src="assets/images/banner2.png" alt="">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- ***** Main Banner Area End ***** -->
   