var sendedUspsFormat = true
var userData = {
  Address1: '',
  Address2: '',
  City: '',
  State: '',
  Zip5: '',
}

var uspsData = {
  Address1: '',
  Address2: '',
  City: '',
  State: '',
  Zip5: '',
}

const errorModal = new bootstrap.Modal('#error-usps')
const selectModal = new bootstrap.Modal('#select-modal')


// Close all modals if error was closed
const errorModalNode = document.getElementById('error-usps')
errorModalNode.addEventListener('hidden.bs.modal', event => {
  hideModals();
})
const selectModalNode = document.getElementById('select-modal')
selectModalNode.addEventListener('hidden.bs.modal', event => {
  hideModals();
})

var addressForm = document.getElementById('address-form')
if (addressForm) {
  addressForm.addEventListener('submit', (event) => {
    addressForm.classList.add('was-validated')
    if (!addressForm.checkValidity()) {
      event.preventDefault()
      event.stopPropagation()
      return;
    }

      // Show  some validity to user 
      //Display loading button
      showButton();
      // Save user filled data
      saveUserInputData();

      // Send request to get data from USPS
      checkDataInUsps();
    }
  )
}

// Function to display loading button
function showButton(loader = true) {
  let textButton = document.getElementById('submit-button-text')
  let loaderButton = document.getElementById('submit-button-loading')
  if (textButton && loaderButton) {
    if (loader) {
      textButton.classList.add('d-none')
      loaderButton.classList.remove('d-none')
    } else {
      loaderButton.classList.add('d-none')
      textButton.classList.remove('d-none')
    }
  }
}

// Hide all modals and display Verify button
function hideModals() {
  showButton(false)
  errorModal.hide();
  selectModal.hide();
}

function saveUserInputData() {
  var data = new FormData(addressForm);
  for (var [key, value] of data) {  
    userData[key] = value
  }
}

// Load USPS data via XHR (Why not simple fetch ?)
function checkDataInUsps() {
  let url = 'https://secure.shippingapis.com/ShippingAPI.dll?API=Verify&XML=';
  var xmlDoc = document.implementation.createDocument(null, "address");

  const body = xmlDoc.createElement('AddressValidateRequest');
  body.setAttribute('USERID',  USPS_USER_ID);
  const Address = xmlDoc.createElement('Address')
  body.appendChild(Address)
  xmlDoc.documentElement.appendChild(body);

  const uspsKeys = [ "Address1", "Address2", "City", "State", "Zip5", "Zip4"] 
  // Append all user data to current 
  
  for (const key of uspsKeys) { 
    const attr = xmlDoc.createElement(key);
    attr.innerHTML = userData[key] || '';
    Address.appendChild(attr);
  }
  
  url += body.outerHTML;
  var request = new XMLHttpRequest();
  request.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var xmlDoc = request.responseXML; //important to use responseXML here
        if (xmlDoc && xmlDoc.childNodes[0]?.childNodes[0]) {
          const nodes = xmlDoc.childNodes[0].childNodes[0].childNodes
          // If we have return error on Address - display error
          if (nodes.length === 1) {
            if (nodes[0].tagName == 'Error') {
              const errTextTag = xmlDoc.getElementsByTagName('Description')
              if (errTextTag.length) {
                displayError('Error on get data from USPS', errTextTag[0].innerHTML )
                return;
              }
            } 
            displayError('Error on get data from USPS', nodes.innerHTML)
            
            
          } else {
            for (const i in nodes) {
              uspsData[nodes[i].tagName] = nodes[i].innerHTML
            }
            printUSPSDataInModal();
            selectModal.show();
          }
        } else {
          displayError('Error on get data from USPS', "Some undefined error")
        }
      } 
  };
  
  request.open("GET", url, true);
  request.send();
}


function printUSPSDataInModal() {
  const status = document.getElementById('saved-status')
  if (status) {
    status.classList.add('d-none')
  }
  const data = sendedUspsFormat ? {...uspsData} : {...userData}
  var div = document.getElementById('selected-format-output')
  if (div) {
    div.innerHTML = `
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Address line 1: ${data.Address1}</li>
        <li class="list-group-item">Address line 2: ${data.Address2}</li>
        <li class="list-group-item">City: ${data.City}</li>
        <li class="list-group-item">State: ${data.State}</li>
        <li class="list-group-item">Zip code: ${data.Zip5}</li>
      </ul>
    `
  }
}

function setSended(uspsFormat) {
  var user = document.getElementById('format-user')
  var usps = document.getElementById('format-usps')
  sendedUspsFormat = uspsFormat;
  if (uspsFormat) {
    user.classList.remove('active')
    usps.classList.add('active')
  } else {
    user.classList.add('active')
    usps.classList.remove('active')
  }
  printUSPSDataInModal();
}


function saveUserData() {
  const data = sendedUspsFormat ? {...uspsData} : {...userData}

  var request = new XMLHttpRequest();
  request.onreadystatechange = async function() {
      if (this.readyState == 4 && this.status == 200) {
        const status = document.getElementById('saved-status')
        const json = JSON.parse(request.responseText);
        if (json.success) {
          if (status) {
            status.innerHTML = json.message
            status.classList.remove('d-none')
            status.classList.remove('bg-danger-subtle')
            status.classList.remove('text-danger-emphasis')
            status.classList.remove('border-danger-emphasis')
            status.classList.add('bg-success-subtle')
            status.classList.add('text-success-emphasis')
            status.classList.add('border-success-emphasis')
            // Close modals when showed success
            var but = document.getElementById('save-address-button')
            if (but) {
              but.classList.add('d-none')
            }
            setTimeout(() => {
              hideModals()
              but.classList.add('d-inline')
            }, 10000)
          }
        } else {
          status.innerHTML = json.error
          status.classList.remove('d-none')
          
          status.classList.add('bg-danger-subtle')
          status.classList.add('text-danger-emphasis')
          status.classList.add('border-danger-emphasis')
          status.classList.remove('bg-success-subtle')
          status.classList.remove('text-success-emphasis')
          status.classList.remove('border-success-emphasis')
        }
      } else {
        if (this.status !== 200 && this.status !== 0) {
          displayError('Error on get data from USPS', "SOme undefined error")
        }
      }
  };
  
  request.open("POST", '/save.php', true);
  request.setRequestHeader('Content-type', 'application/json; charset=utf-8');
  request.send(JSON.stringify(data));
}


function displayError(title = 'Error', message = "Internal error") {
  const ttl = document.getElementById('error-title')
  if (ttl) {
    ttl.innerHTML = title
  }
  const err = document.getElementById('error-message')
  if (err) {
    err.innerHTML = message
  }
  errorModal.show();
}