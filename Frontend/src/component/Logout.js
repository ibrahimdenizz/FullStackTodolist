import React, { useEffect } from 'react';

const Logout = () => {

    useEffect(() => {
        localStorage.removeItem('token');
        window.location = "/login"
    },[])

    return ( <div></div>  );
}
 
export default Logout;