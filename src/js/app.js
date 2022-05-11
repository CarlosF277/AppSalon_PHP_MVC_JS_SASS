let paso = 1; //paso inicial para el paginador en todo el codigo
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    nombre: "",
    fecha: "",
    hora: "",
    servicios: []
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
    
    nombreCliente(); //Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); //añade la fecha de la cita al objeto
    seleccionarHora(); //Añade la hora de la cita al objeto

    mostrarResumen(); //muestra el resumen de la cita

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

            //resumen
          /*  if(paso === 3){
                mostrarResumen();
            }*/
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
        mostrarResumen(); //para activar la funcion con los botontes del paginador
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
    

    //identificar el elemento al que se le da clic
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);


    //comporbar si un servicio ya fue agregado 
    if(servicios.some(agregado => agregado.id === id) ){
        //Eliminar servicio agregado
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove("seleccionado");

    }
    else{
        //agregarlo
        
        cita.servicios = [...servicios, servicio]; //... para separar el array e ingresar los elementos en otro junto con el nuevo servicio
        divServicio.classList.add("seleccionado");
    }

    console.log(cita);
}

function nombreCliente(){
    //asignar nombre del formulario al objeto
    cita.nombre = document.querySelector("#nombre").value;
}

function seleccionarFecha(){
    const inputFecha = document.querySelector("#fecha");

    inputFecha.addEventListener("input", function(e) { //event
       // console.log(inputFecha.value);
       //para permitir la seleccion de todos los dias menos el sabado y domingo

       const dia = new Date(e.target.value).getUTCDay();

       if([6, 0].includes(dia)){
           //console.log("Sabados y domingos no abrimos")
           e.target.value = ""; //para no asignar nada en este caso
           mostrarAlerta("Fines de semana no permitidos", "error");
       }
       else{
           //console.log("Correcto");
           cita.fecha = e.target.value; //ingresa la fecha al objeto
       }

    });
}

function seleccionarHora (){
    const inputHora = document.querySelector("#hora");
    inputHora.addEventListener("input", e => {
        console.log(e.target.value);

        cita.hora = ""; //para mantener la propiedad el objeto vacia en caso de que no sea correcta la seleccion
        
        const hora = horaCita.split(":")[0];
        if(hora < 10 || hora > 18){

            e.target.value = "";
            cita.hora = "";
            console.log("Horas no validas");
            mostrarAlerta("Hora no valida", "error");
        }
        else{
            
            console.log("horas validas")
            cita.hora = e.target.value;
            console.log(cita);
        }
    });
}

function mostrarAlerta(mensaje, tipo){

    //evita la creacion de multiples alertas
    const alertaPrevia = document.querySelector(".alerta");

    if(alertaPrevia) return; //if en una sola linea

    //scripting para generar la alerta
    const alerta = document.createElement("DIV");
    alerta.textContent = mensaje;
    alerta.classList.add("alerta");
    alerta.classList.add(tipo);

    const formulario = document.querySelector(".formulario");
    formulario.appendChild(alerta);
    console.log(alerta);

    //quita la alerta despues de 3 segundos
    setTimeout(() =>{
        alerta.remove();
    },3000);
}

function mostrarResumen(){

    const resumen = document.querySelector(".contenido-resumen");

    console.log(Object.values(cita));

    if(Object.values(cita).includes("")){
        console.log("Hacen falta datos");
    }
    else{
        console.log("Todo bien");
    }
}