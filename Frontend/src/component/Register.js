import React ,{useState} from 'react';
import Joi from 'joi-browser';
import useForm from './common/useForm';
import { register } from '../service/userService';

function Register(props) {
    const [user,setUser] = useState({name:"",email:"",password:""});
    const [errors,setErrors] = useState({name:"",email:"",password:""});
    const schema = {
        name : Joi.string().min(5).required().label("Name"),
        email : Joi.string().email({minDomainAtoms:2}).label("Email").required() ,
        password : Joi.string().min(5).label("Password").required()
    }

    const submit = async ()=> {
        console.log("Submitted");
        try{
        await register(user.email,user.name,user.password);
        props.history.replace("/login");
        }catch(ex) {
            if (ex.response.status===409) {
                setErrors({...errors, name : ex.response.data.message});
            }else {
                setErrors({...errors, name : "Unexpected Error"});
            }
        }
    } 
    const [handleSubmit,renderInput,renderButton] = useForm(submit,user,setUser,errors,setErrors,schema);


    return (
        <div id="login-form">
        <form onSubmit={handleSubmit}>
            {renderInput("name",user.name,"text",errors.name,"Name")}
            {renderInput("email",user.email,"email",errors.email,"Email adress")}
            {renderInput("password",user.password,"password",errors.password,"Password")}
            {renderButton("Submit")}
        </form>
    </div>
    );
}

export default Register;