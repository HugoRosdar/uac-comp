async function getCsrfToken(){
  try{
    const res = await fetch('/sanctum/csrf-cookie', {
      method: 'GET',
      credentials: 'include'
    });
    return true;
  }catch(e){
    console.error('Error getting CSRF token:', e);
    return false;
  }
}

async function apiFetch(path, method='GET', body=null){
  const token = localStorage.getItem('token')||'';
  const opts = { 
    method, 
    headers: {'Content-Type':'application/json'},
    signal: AbortSignal.timeout(30000),
    credentials: 'include'
  };
  if(token) opts.headers['Authorization']='Bearer '+token;
  if(body) opts.body = JSON.stringify(body);
  
  console.log('API Request:', method, '/api'+path, body);
  
  try{
    const res = await fetch('/api'+path, opts);
    const data = await res.json().catch(()=>({}));
    
    console.log('API Response:', res.status, data);
    
    // Only redirect to login if we have a token and it's expired (401)
    // Don't redirect for login endpoint (which should handle 401 as authentication failure)
    if(res.status === 401 && token && !path.includes('/auth/login')){
      localStorage.removeItem('token');
      window.location = 'index.html';
      return { ok: false, status: 401, data: {error: 'Sesion expirada'} };
    }
    
    return { ok: res.ok, status: res.status, data };
  }catch(e){
    console.error('API Error:', e.message);
    return { ok: false, status: 0, data: {error: e.message || 'Error de conexion'} };
  }
}

function downloadBlob(filename, blob){
  try{
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a'); 
    a.href = url; 
    a.download = filename; 
    document.body.appendChild(a); 
    a.click(); 
    a.remove();
    URL.revokeObjectURL(url);
  }catch(e){
    console.error('Error descargando:', e);
    alert('Error al descargar');
  }
}
