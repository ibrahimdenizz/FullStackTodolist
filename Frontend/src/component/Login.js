import React, { useState} from 'react';
import "../css/main.css";
import Joi from "joi-browser";
import useForm from './common/useForm';
import {login} from "../service/userService";

function Login() {
    const [user,setUser] = useState({email:"",password:""});
    const [errors,setErrors] = useState({email:"",password:""});
    const schema = {
        email : Joi.string().email({minDomainAtoms : 2}).label("Email").required(),
        password : Joi.string().required().min(5).label("Password")
    }
    const submit = async () => {
        console.log("Submitted");
        try{
        await login(user.email,user.password);
        window.location = "/todolist/1"
        }catch (ex) {
            if(ex.response.status === 401){
                setErrors({...errors, password : "password doesn't match"});
            }else if(ex.response.status === 404){
                setErrors({...errors, email : "Email wasn't founded"});
            }else {
                setErrors({...errors, email : "Unexpected Error"});

            }
        }
    }
    const [handleSubmit,renderInput,renderButton] = useForm(submit,user,setUser,errors,setErrors,schema)
    return (
        <div id="login-form">
            <form noValidate onSubmit={handleSubmit} >
                {renderInput("email",user.email,"email",errors.email,"Email adress")}                
                {renderInput("password",user.password,"password",errors.password,"Password")}
                {renderButton("Submit")}
            </form>
        </div>
    );
}

export default Login;