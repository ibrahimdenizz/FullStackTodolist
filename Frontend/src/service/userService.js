import http from "./httpService";
import {url} from "../config.json";
import jwtDecode from "jwt-decode";

const endPointUrlForLogin = url + "auth";
const endPointUrlForRegister = url + "users";

export const login = async (email,password) => {
    const {data} = await http.post(endPointUrlForLogin,{email,password});
    localStorage.setItem('token',data.jwt);
}

export const getCurrentUser = () => {
    try {
        const jwt = localStorage.getItem('token')
        return jwtDecode(jwt).data;
    }catch {
        return null;
    }
}

export const register = async (email,name,password) => {
    const {data} = await http.post(endPointUrlForRegister,{email,name,password});
    console.log(data.message);
}

export default {
    login,
    getCurrentUser,
    register
}