// api helper and UI utilities
async function apiFetch(path, method='GET', body=null){
  const token = localStorage.getItem('token')||'';
  const opts = { method, headers: {'Content-Type':'application/json'} };
  if(token) opts.headers['Authorization']='Bearer '+token;
  if(body) opts.body = JSON.stringify(body);
  const res = await fetch('/api'+path, opts);
  const data = await res.json().catch(()=>({}));
  return { ok: res.ok, status: res.status, data };
}
function downloadBlob(filename, blob){
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a'); a.href = url; a.download = filename; document.body.appendChild(a); a.click(); a.remove();
  URL.revokeObjectURL(url);
}
