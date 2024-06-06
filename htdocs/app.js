const form = document.getElementById('booking-form');
const response = document.getElementById('response');

form.addEventListener('submit', async (e) => {
  e.preventDefault();

  const formData = new FormData(form);
  const name = formData.get('name');
  const email = formData.get('email');
  const date = formData.get('date');
  const quantity = formData.get('quantity');  
    
  try {
    const response = await fetch('/api/bookings', {
      method: 'POST',
      body: JSON.stringify({ name, email, quantity, date }),
      headers: { 'Content-Type': 'application/json' },
    });

    if (!response.ok) {
      throw new Error('Error creating booking');
    }

    response.json().then((data) => {
      response.innerHTML = `<p>Booking created successfully with ID ${data.id}</p>`;
    });
  } catch (error) {
    response.innerHTML = `<p>Error: ${error.message}</p>`;
  }
});