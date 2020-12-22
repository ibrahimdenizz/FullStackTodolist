import React from 'react';
import Joi  from 'joi-browser';


const useForm = (submit,values,setValues,errors,setErrors,schema) => {

    const handleChange = e => {
        const {name,value}=e.target
        setValues({
            ...values,
            [name]: value
        })

        const {error} = Joi.validate(value,schema[name],{ abortEarly: false });
    
        if(error) {
           setErrors({
               ...errors,
               [name] : error.details[0].message
           }); 
        }else {
            setErrors({
                ...errors,
                [name] : ""
            })
        }
    }
    
    const handleSubmit = e => {
        e.preventDefault();
        submit();
    }

    const renderInput = (name,value,type,error,label) => {
        return (
        <div className="form-group">
            <label htmlFor={name}>{label}</label>
            <input  
                type={type}
                className={error==="" ? "form-control" : "form-control is-invalid"}  
                name={name}
                id={name}
                value={value}
                onChange={handleChange} />
            {error === "" ? "" : 
            <div id="validationServer03Feedback" className="invalid-feedback">
            {error}
            </div>}
        </div>);
    }

    const handleDisabled = () => {
        for (const element in values) {
            if(!values[element])
            return "disabled"
            
        }
        for (const error in errors) {
            if(errors[error])
            return "disabled"
            
        }
        return null;
    }

    const renderButton = (name) => {
        return (
        <button type="submit" className="btn btn-primary" disabled={handleDisabled()} >{name}</button>
        );
    }

    return [
        handleSubmit,
        renderInput,
        renderButton
    ]

}
 
export default useForm;