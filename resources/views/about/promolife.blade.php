@extends('layouts.app')

@section('content')
   
    <div class="card-body">

      <div class="banner" >
        <img class="logoCompany" src="{{ asset('/img/promolife.png') }}" alt="bhtrade">
      </div>


      <div style="padding-bottom: 100px;"  class="separador"></div>

        <section class="header">

            <div class="row mt-4">
                <div class="col md-6">
                   <div class="card info">
                    <h3 style="margin-bottom: 30px;">¿Qué es Promo Life? </h3>
                    <p class="mb-4" style=" text-align: justify;
                    text-justify: inter-word; font-size:1.2rem;"><b> PROMOTIONAL GLOBAL SUPPLIER.</b> Más de una década
                        importando, fabricando y distribuyendo productos
                        promocionales y regalos corporativos para las marcas
                        más prestigiosas. Expertos asesores, socios estratégicos
                        y facilitadores para la adquisición del producto exacto
                        para cada campaña externa o interna de las empresas
                        AAA.</p>
                   </div>
                    
                </div>
                <div class="col md-6">
                  <div class="card info">
                    <h3 style="margin-bottom: 30px;">Historia </h3>
                    <p style=" text-align: justify;
                    text-justify: inter-word; font-size:1.2rem;">En 2011 se constituye <b>Promo Life S de RL de CV</b> ,
                        sumando la experiencia de los socios en la
                        importación y comercialización de productos
                        promocionales disponibles en México. Hemos
                        crecido año con año sin detenernos, teniendo cada
                        vez más clientes, presencia en el mercado y un
                        equipo de colaboradores más robusto y
                        profesionalizando cada vez más nuestra dinámica
                        de trabajo.</p>
                  </div>                    
                </div>
            </div>
        </section>
          
        <div class="d-flex justify-content-center">
          <div class="valores">
            <p  style="color:#ffffff;text-align: center; font-size:1.4rem;">CÓDIGO DE VALORES </p>  
          </div>
        </div>
       
        <div class="row mt-4 mb-4 ">
        
        </div>


          <section class="timeline">
            <div class="container">

                <div class="timeline-item">
                    <div class="timeline-img"></div>
                    <div class="timeline-content js--fadeInLeft">
                    <div class="date">LEALTAD </div>
                    <div style="padding-bottom: 30px;"  class="separador"></div>

                    <p class="mt-4" style=" text-align: justify; text-justify: inter-word; font-size:1rem;"> 
                    <b>Es sin duda una de las cualidades
                        más respetables de un ser humano,
                        en especial cuando se trata de una
                        relación de pareja o de una
                        amistad ya que ayuda a mantener
                        un lazo fuerte y generar confianza
                        en el otro</b>
                        </p>
                        <div style="padding-bottom: 50px;"  class="separador"></div>

                        <img class="item-img" src="{{ asset('/img/lealtad.png') }}" alt="">
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-img"></div>
                    <div class="timeline-content js--fadeInRight">                
                        <div class="date">CONFIANZA</div>
                        <p class="mt-4" style=" text-align: justify; text-justify: inter-word; font-size:1rem;"> 
                        <b>Nos referimos a la posibilidad de
                            creer en que otra persona, o un
                            grupo de ellas, actuarán de la
                            manera adecuada en nuestra
                            ausencia, es decir, que no nos
                            defraudarán o engañarán, ni
                            necesitan tampoco nuestra
                            supervisión y vigilancia.</b>
                        </p>

                        <div style="padding-bottom: 100px;"  class="separador"></div>

                        <img style="width: 80%;" class="item-img" src="{{ asset('/img/confianza.png') }}" alt="">

                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-img"></div>
                    <div class="timeline-content js--fadeInLeft">
                        <div class="date">HONESTIDAD</div>
                    <div style="padding-bottom: 30px;"  class="separador"></div>

                    <p class="mt-4" style=" text-align: justify; text-justify: inter-word;font-size:1rem;"> 
                    <b>Es una virtud humana
                        consistente en el amor a la
                        justicia y la verdad por
                        encima del beneficio personal
                        o de la conveniencia.</b>
                        </p>

                        <div style="padding-bottom: 120px;"  class="separador"></div>

                        <img style="width: 70%" class="item-img" src="{{ asset('/img/honestidad.png') }}" alt="">
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-img"></div>
                    <div class="timeline-content js--fadeInRight">                
                        <div class="date">TRABAJO EN EQUIPO </div>
                        <p class="mt-4" style=" text-align: justify; text-justify: inter-word;font-size:1rem;"> 
                        <b>Incluye aquellas labores que se
                            realizan de manera compartida
                            y organizada, en las que cada
                            quien asume una parte y todos
                            tienen el mismo objetivo en
                            común. Se trata de una forma
                            de organización del trabajo
                            basada en el compañerismo</b>
                        </p>

                        <div style="padding-bottom: 80px;"  class="separador"></div>

                        <img  class="item-img" src="{{ asset('/img/equipo.png') }}" alt="">
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-img"></div>
                    <div class="timeline-content js--fadeInLeft">
                    <div class="date">PROACTIVIDAD </div>
                    <div style="padding-bottom: 30px;"  class="separador"></div>

                    <p class="mt-4" style=" text-align: justify; text-justify: inter-word;font-size:1rem;"> 
                    <b>Es un tipo de comportamiento
                        de tipo anticipatorio, que no
                        requiere de un estímulo externo
                        para iniciar una acción o
                        emprender un cambio.
                        </b>
                        </p>

                        <div style="padding-bottom: 120px;"  class="separador"></div>

                        <img class="item-img" src="{{ asset('/img/proactividad.png') }}" alt="">
                    </div>
                </div>
          </section>

        <div style="padding-bottom: 80px;"  class="separador"></div>

        <div class="d-flex justify-content-center">
          <div class="resultados">
            <p  style="color:#ffffff;text-align: center; font-size:1.4rem;">ORIENTADO A <br> RESULTADOS </p>  
          </div>
        </div>
       
    </div>
@stop


@section('styles')
<style>

.banner{
  width: 100%; 
  height:250px; 
  background-image:linear-gradient( rgba(76, 216, 255, 0.8), rgba(30, 108, 217, 0.8)),
   url('https://media-exp1.licdn.com/dms/image/C4E1BAQHZpvSzm3mrGg/company-background_10000/0/1604596643746?e=2159024400&v=beta&t=7eQro0ejdMhEJ8UTKMZ2fEjTZmbCc6KtEm5kg-LeXIw');
  background-repeat: no-repeat;
	background-size: cover;
  display:flex; 
  justify-content:center;
  background-position: center center;
  border-radius: 10px;
}

.logoCompany{
  width: 300px; 
  height:100px; 
  margin-top:200px;
}

.item-img{
    width: 350px;
    position: absolute;
    margin-top:-120px; 
    margin-left: 10%;
}

.info{
  padding: 20px;
}

.valores{
  width: 0;
 height: 0;
 border-left: 200px solid transparent;
 border-right: 200px solid transparent;
 border-top: 150px solid #002D69;

}


.valores p {
  width: 400px;
  margin: -120px 0  0  -200px;
}


.resultados{
  width: 0;
 height: 0;
 border-left: 200px solid transparent;
 border-right: 200px solid transparent;
 border-top: 150px solid #002D69;


}

.resultados p {
  width: 400px;
  margin: -120px 0  0  -200px;
}


.timeline {
  position: relative;
}
.timeline::before {
  content: "";
  background: #c5cae9;
  width: 5px;
  height: 95%;
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
}

.timeline-item {
  width: 100%;
  margin-bottom: 25px;
}
.timeline-item:nth-child(even) .timeline-content {
  float: right;
  padding: 40px 30px 10px 30px;
}
.timeline-item:nth-child(even) .timeline-content .date {
    right: auto;
    left: 20%;
    width: 60%;
    text-align: center;
}
.timeline-item:nth-child(even) .timeline-content::after {
  content: "";
  position: absolute;
  border-style: solid;
  width: 0;
  height: 0;
  top: 30px;
  left: -15px;
  border-width: 10px 15px 10px 0;
  border-color: transparent #f5f5f5 transparent transparent;
}
.timeline-item::after {
  content: "";
  display: block;
  clear: both;
}

.timeline-content {
  position: relative;
  width: 45%;
  padding: 10px 30px;
  border-radius: 4px;
  background: #f5f5f5;
  box-shadow: 0 20px 25px -15px rgba(0, 0, 0, 0.3);
}
.timeline-content::after {
  content: "";
  position: absolute;
  border-style: solid;
  width: 0;
  height: 0;
  top: 30px;
  right: -15px;
  border-width: 10px 0 10px 15px;
  border-color: transparent transparent transparent #f5f5f5;
}

.timeline-img {
  width: 30px;
  height: 30px;
  background: #3f51b5;
  border-radius: 50%;
  position: absolute;
  left: 50%;
  margin-top: 25px;
  margin-left: -15px;
}


.timeline-card {
  padding: 0 !important;
}
.timeline-card p {
  padding: 0 20px;
}
.timeline-card a {
  margin-left: 20px;
}

.timeline-img-header {
  height: 200px;
  position: relative;
  margin-bottom: 20px;
}
.timeline-img-header h2 {
  color: #ffffff;
  position: absolute;
  bottom: 5px;
  left: 20px;
}

blockquote {
  margin-top: 30px;
  color: #757575;
  border-left-color: #3f51b5;
  padding: 0 20px;
}

.date {
    background: #002D69;
    display: block;
    color: #ffffff;
    padding: 15px;
    position: absolute;
    top: -20px;
    right: 20%; 
    border-radius: 5px;
    font-size: 1.2rem;
    width: 60%;
    text-align: center;
}

@media screen and (max-width: 768px) {

  #sidebar ~ #main{
                padding: 0;
  }

  .logoCompany{
    width: 280px; 
    height:90px; 
    margin-top:200px;
  }
    .item-img{
    display: none;
    }

  .timeline-item {
    margin-top: 80px;
  }

  .timeline::before {
    left: 50px;
  }
  .timeline .timeline-img {
    left: 50px;
  }
  .timeline .timeline-content {
    max-width: 80%;
    width: auto;
    margin-left: 70px;
  
  }
  .timeline .timeline-item:nth-child(even) .timeline-content {
    float: none;
  }
  .timeline .timeline-item:nth-child(odd) .timeline-content::after {
    content: "";
    position: absolute;
    border-style: solid;
    width: 0;
    height: 0;
    top: 30px;
    left: -15px;
    border-width: 10px 15px 10px 0;
    border-color: transparent #f5f5f5 transparent transparent;
  }

  .date{
    font-size: 12px;
  }

}

</style>
@stop
