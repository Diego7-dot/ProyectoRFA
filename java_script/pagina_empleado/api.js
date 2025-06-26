const BASE_URL = 'https://api.botpress.cloud';
const ACCESS_TOKEN = 'bp_pat_JVnzRB14ZigUOIMIH8TZN2Wj6FLmIAwAasPO'; 

/**
 * Función para obtener las conversaciones desde la API de Botpress
 * @param {string|null} nextToken 
 * @returns {Promise<Object>} 
 */
async function fetchConversaciones(nextToken = null) {
  let url = `${BASE_URL}/v1/chat/conversations`;
  if (nextToken) {
    url += `?nextToken=${encodeURIComponent(nextToken)}`;
  }
  
  try {
    const response = await fetch(url, {
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${ACCESS_TOKEN}`
      }
    });
    if (!response.ok) {
      throw new Error(`Error ${response.status}: ${response.statusText}`);
    }
    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error al obtener las conversaciones:', error);
    throw error;
  }
}

export { fetchConversaciones };
