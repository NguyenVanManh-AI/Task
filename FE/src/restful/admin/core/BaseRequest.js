const axios = require('axios');
import config from '../../../config.js'
import router from './../../../router/routes' 
import useEventBus from '../../../composables/useEventBus'

let dataAdmin = window.localStorage.getItem('admin');
let admin = null;
if(dataAdmin){
	admin = JSON.parse(dataAdmin);
}

export default {
	_getHeaders(){
		let headers = {}; 
		if(admin !== null){
			headers.Authorization = 'Bearer ' + admin.access_token;
		}
		return headers;
	},

	get(url){
		return new Promise( (resolve, reject) =>{ 
			axios.get(
				config.API_URL + '/' + url  , 
				{
					headers : this._getHeaders()
				}
			)
			.then( response =>{
				resolve(response.data);
			})
			.catch( error => {
				if(error.response.status == 401) this.hadleError401();
				else {
					let errors = {
						message : error.message,
						status : error.response.status
					}
					window.localStorage.setItem('error',JSON.stringify(errors));
					reject(error);
				}
			})
		});
	},
	post(url,data){
		return new Promise( (resolve, reject) =>{
			axios.post(
				config.API_URL + '/' + url, 
				data,
				{
					headers : this._getHeaders()
				}
			)
			.then( response =>{
				resolve(response.data);
			})
			.catch( error => {
				if(error.response.status == 401) this.hadleError401();
				else {
					let errors = {
						message : error.message,
						status : error.response.status
					}
					window.localStorage.setItem('error',JSON.stringify(errors));
					reject(error);
				}
			})
		})
	},
	put(url,data){
		return new Promise( (resolve, reject) =>{
			axios.put(
				config.API_URL + '/' + url, 
				data,
				{
					headers : this._getHeaders()
				}
			)
			.then( response =>{
				resolve(response.data);
			})
			.catch( error => {
				if(error.response.status == 401) this.hadleError401();
				else {
					let errors = {
						message : error.message,
						status : error.response.status
					}
					window.localStorage.setItem('error',JSON.stringify(errors));
					reject(error);
				}
			})
		})
	},
	patch(url,data){
		return new Promise( (resolve, reject) =>{
			axios.patch(
				config.API_URL + '/' + url, 
				data,
				{
					headers : this._getHeaders()
				}
			)
			.then( response =>{
				resolve(response.data);
			})
			.catch( error => {
				if(error.response.status == 401) this.hadleError401();
				else {
					let errors = {
						message : error.message,
						status : error.response.status
					}
					window.localStorage.setItem('error',JSON.stringify(errors));
					reject(error);
				}
			})
		})
	},
	delete(url, data){
		return new Promise( (resolve, reject) =>{
			axios.delete(
				config.API_URL + '/' + url, 
				data,
				{
					headers : this._getHeaders()
				}
			)
			.then( response =>{
				resolve(response.data);
			})
			.catch( error => {
				if(error.response.status == 401) this.hadleError401();
				else {
					let errors = {
						message : error.message,
						status : error.response.status
					}
					window.localStorage.setItem('error',JSON.stringify(errors));
					reject(error);
				}
			})
		})
	},

	hadleError401(){
		const { emitEvent } = useEventBus();
		emitEvent('eventError401','Unauthorized 401');
		window.localStorage.removeItem('admin');

		setTimeout(()=>{
			router.push({name:"LoginAdmin"});
			window.localStorage.removeItem('error');
			window.location=window.location.href;
		}, 1500);
	}

}

