function handleResponse(response) {
  if (response.status === 204) {
    return null;
  }

  if (response.status === 501) {
    return response.json().then(() => {
      throw new Error('The function is not implemented on the server.');
    });
  }

  if (response.status === 404) {
    return response.json().then(() => {
      throw new Error('Not found.');
    });
  }

  if (!response.ok) {
    return response.json().then((error) => {
      const message = error?.message || 'An unexpected error occurred.';
      throw new Error(message);
    });
  }
  
  return response.json();
}


function getCountries() {
  const url = '/api/project/countries';
  return fetch(url)
    .then(handleResponse)
    .then((countries) => {
      countries = countries.sort();
      console.log('Available countries:', countries);
      if (countries && countries.length > 0) {
        localStorage.setItem('countriesList', JSON.stringify(countries));
      } else {
        localStorage.removeItem('countriesList');
      }
      return countries; // Return the countries for chaining
    })
    .catch((error) => {
      console.error('Error fetching countries:', error);
      localStorage.removeItem('countriesList'); // Clean up on error
      throw error; // Re-throw to allow calling code to handle it
    });
}

var countries = getCountries();
