let paso = 1; //paso inicial para el paginador en todo el codigo
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    nombre: "",
    fecha: "",
    hora: "",
    servicios: ""
};


document.addEventListener("DOMContentLoaded", function(){
    iniciarApp();
});

function iniciarApp(){

    mostrarSeccion() //muestra y oculta las secciones, para que aparezca primero la primera
    tabs(); //cambia la seccion cuando se presionen los tabs
    botonesPaginador(); //agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); //Consulta la API en el backend de php

}

function mostrarSeccion(){

    //ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector(".mostrar");
    if(seccionAnterior){
        seccionAnterior.classList.remove("mostrar"); //comprobar si existe la clase de mostrar y despues eliminar
    }

    //seleccionar la seccion con el paso
    const seccion = document.querySelector(`#paso-${paso}`);
    seccion.classList.add("mostrar");

    //quita la clase de actual a la clase anteriir
    const tabAnterior = document.querySelector(".actual");
    if(tabAnterior){
        tabAnterior.classList.remove("actual");
        
    }

    //resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);//selector de atributo
    tab.classList.add("actual");
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');
    //console.log(botones);
    //iterar sobre los resultados e ir asociando el clic en cada uno de ellos

    botones.forEach( boton => {
        boton.addEventListener("click", function(e){ //e es el evento a registrar, lo pasa el addEventListener

            //console.log(parseInt (e.target.dataset.paso)); //convertir a enteros
            paso = parseInt (e.target.dataset.paso);

            //funcion
            mostrarSeccion();  
            botonesPaginador();
        });
    });
}

function botonesPaginador(){
    const paginaAnterior = document.querySelector("#anterior");
    const paginaSiguiente = document.querySelector("#siguiente");

    if(paso === 1){
        paginaAnterior.classList.add("ocultar");
        paginaSiguiente.classList.remove("ocultar");
    }
    else if(paso === 3){
        paginaAnterior.classList.remove("ocultar");
        paginaSiguiente.classList.add("ocultar");
    }
    else{
        paginaAnterior.classList.remove("ocultar");
        paginaSiguiente.classList.remove("ocultar");
    }

    mostrarSeccion();
    
}

function paginaAnterior (){
    const paginaAnterior = document.querySelector("#anterior");
    paginaAnterior.addEventListener("click", function(){
        
        if(paso <= pasoInicial){
            return;
        } 
        else{
            paso--;
        }

        botonesPaginador();
    });

}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector("#siguiente");
    paginaSiguiente.addEventListener("click", function(){
        
        if(paso >= pasoFinal){
            return;
        } 
        else{
            paso++;
        }

        botonesPaginador();
    });

}

async function consultarAPI(){

    try {
        const url = "http://localhost:3000/api/servicios";
        const resultado = await fetch(url); //espera hasta hacer el fetch por completo
        const servicios = await resultado.json();
        
        //console.log(servicios);
        mostrarServicios(servicios);
        
    } catch (error) {
        
    }

}

function mostrarServicios(servicios){
    servicios.forEach (servicio =>{
        const {id, nombre, precio} = servicio; //destructuring

        const nombreServicio = document.createElement("P");
        nombreServicio.classList.add("nombre-servicio");
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement("P");
        precioServicio.classList.add("precio-servicio");
        precioServicio.textContent = `$ ${precio}`;

        const servicioDiv = document.createElement("DIV");
        servicioDiv.classList.add("servicio");
        servicioDiv.dataset.idServicio = id; //agrega la etiqeuta de data, data-id-servicio="id"

        servicioDiv.onclick = function () {
            seleccionarServicio(servicio);
        };

        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        //inyectar el codigo en servicios
        document.querySelector("#servicios").appendChild(servicioDiv);
    })
}

function seleccionarServicio (servicio){
    const {id} = servicio;
    const {servicios} = cita;
    cita.servicios = [...servicios, servicio];

    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);
    divServicio.classList.add("seleccionado");
    console.log(cita);
}

