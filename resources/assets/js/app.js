var tesisCalificar = new Vue({
	el: '#app',

	created: function(){
		//this.getCalificaciones(idtesis);
	},

	data: {
		titulo: '',
		idtesis: '',
		cal1 : 0,
		cal2 : 0,
		cal3 : 0,
		cal4 : 0,
		cal5 : 0,
		prom : 0,
		cals : [],
		editar1 : false,
		editar2 : false,
		editar3 : false,
		editar4 : false,
		editar5 : false,
		eval : 0,
		nCal : 0,
		obs	 : ''

	},

	computed: {
	},

	methods: {

		getCalificaciones: function(idt){
			var urlCal = 'cal?idtesis=' + idt + '&d=v';
			this.idtesis = idt;
			axios.get(urlCal).then(function(response) {
				this.cals = response.data.ct;
				this.eval = Number(response.data.ct[response.data.ct.length-1].eval + 1);
				this.prom = promedioCalcula();
				this.cal1 = vd(this.cals[0].cal);
				this.cal2 = vd(this.cals[1].cal);
				this.cal3 = vd(this.cals[2].cal);
				this.cal4 = vd(this.cals[3].cal);
				this.cal5 = vd(this.cals[4].cal);
			});
			return;
		},


		calificaTesis: function(idtesis){
			this.idtesis = idtesis;
			$('#califica').modal('show');
		},

		nuevaCal: function(){
			var url = 'store';			
			axios.post(url,{idtesis:this.idtesis , eval:this.eval , cal:this.nCal , obs:this.obs}).then(response => {
				//console.log(response.data);
				$('#califica').modal('hide');
				//this.getCalificaciones(this.idtesis);
				window.location.href = "/cal?idtesis=" + this.idtesis + "&d=l";
			}).catch(function(error){
				console.log(error.response.data);
			});
		},

		editaCal: function(Cal){
			//console.log(Cal.eval);						
			switch(Cal.eval){
				case 1 : this.editar1 = true; break;
				case 2 : this.editar2 = true; break;
				case 3 : this.editar3 = true; break;
				case 4 : this.editar4 = true; break;
				case 5 : this.editar5 = true; break;
			}				
		},

		guardaCal : function(Cal){
			var url = 'cal/' + Cal.id + '/';
			var c = 0;
			switch(Cal.eval){
				case 1 : c = this.cal1;break;
				case 2 : c = this.cal2;break;
				case 3 : c = this.cal3;break;
				case 4 : c = this.cal4;break;
				case 5 : c = this.cal5;break;
			}
			url += c;
			this.cals[Cal.eval-1].cal = c;
			//console.log(url);
			
			axios.post(url).then(response => {
				console.log(response.data);				
				switch(Cal.eval){
					case 1 : this.editar1 = false;break;
					case 2 : this.editar2 = false;break;
					case 3 : this.editar3 = false;break;
					case 4 : this.editar4 = false;break;
					case 5 : this.editar5 = false;break;
				}	
				//recualcula el promedio
				this.prom = promedioCalcula();	
				toastr.success("La calificación se actualizó correctamente");

			}).catch(function(error){
				console.log(error.response.data);
			});
		},
		eliminaCal(Cal){
			var url = 'eliminaCal/' + Cal.id;
			if(confirm("¿Está seguro de eliminar esta calificación?")){
				axios.get(url).then(response =>{
					toastr.info("La calificación ha sido eliminada correctamente","Aviso",{timeOut:3000});
					var redirect = function(){
						window.location.href = "/cal?idtesis=" + tesisCalificar.idtesis + "&d=l";
					}
					setTimeout(redirect,2000);
				});
			}
		}
	}

});


promedioCalcula = function(){
	var s = i = 0;
	for(var c in tesisCalificar.cals){
		s += Number(tesisCalificar.cals[c].cal);
		i++;
	};
	console.log(s+' - '+i);
	return Math.round(s/i*10)/10;
}

vd = function(v){
	if(!Number.isNaN(v)){
		return v;
	}else{
		return 0;
	}
}
