<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Save you mailing address</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link href="index.css" rel="stylesheet">
  <script>
    var USPS_USER_ID = "<?php echo $_ENV['USPS_USER_ID'] ; ?>"
  </script>
</head>
<body>
  <main class="page">
    <div class="container p-5 text-left">
      <h3>Address Form </h3>
      <h5>Validate/Standardize address using USPS</h5>
    </div>
    <div class="container bg-light rounded-3 p-4">
      <section class="form-section ">
        <form class="needs-validation" action="javascript:void(0);" id="address-form" >
          <div class="row">
            <div class="col-12 mb-3">
              <label for="Address1" class="form-label text-black-50">Address line 1</label>
              <input class="form-control" name="Address1" id="Address1" minlength="3" placeholder="Address line 1" autocomplete="address-line1"/>
              <div class="invalid-feedback"> Please fill field with minimum of 3 characters </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-3">
              <label for="Address2" class="form-label text-black-50">Address line 2</label>
              <input class="form-control" name="Address2" id="Address2" required minlength="3" placeholder="Address line 2" autocomplete="address-line2"/>
              <div class="invalid-feedback"> Please fill field with minimum of 3 characters </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-3">
              <label for="City" class="form-label text-black-50">City</label>
              <input class="form-control" name="City" id="City" required minlength="3" placeholder="City" autocomplete="address-level2"/>
              <div class="invalid-feedback"> Please fill field with minimum of 3 characters </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-3">
              <label for="State" class="form-label text-black-50">State</label>
              <select class="form-control" id="State" name="State" required placeholder="State" autocomplete="address-level1">
                <option value=""></option>
                <option value="AK">Alaska</option>
                <option value="AL">Alabama</option>
                <option value="AR">Arkansas</option>
                <option value="AZ">Arizona</option>
                <option value="CA">California</option>
                <option value="CO">Colorado</option>
                <option value="CT">Connecticut</option>
                <option value="DC">District of Columbia</option>
                <option value="DE">Delaware</option>
                <option value="FL">Florida</option>
                <option value="GA">Georgia</option>
                <option value="HI">Hawaii</option>
                <option value="IA">Iowa</option>
                <option value="ID">Idaho</option>
                <option value="IL">Illinois</option>
                <option value="IN">Indiana</option>
                <option value="KS">Kansas</option>
                <option value="KY">Kentucky</option>
                <option value="LA">Louisiana</option>
                <option value="MA">Massachusetts</option>
                <option value="MD">Maryland</option>
                <option value="ME">Maine</option>
                <option value="MI">Michigan</option>
                <option value="MN">Minnesota</option>
                <option value="MO">Missouri</option>
                <option value="MS">Mississippi</option>
                <option value="MT">Montana</option>
                <option value="NC">North Carolina</option>
                <option value="ND">North Dakota</option>
                <option value="NE">Nebraska</option>
                <option value="NH">New Hampshire</option>
                <option value="NJ">New Jersey</option>
                <option value="NM">New Mexico</option>
                <option value="NV">Nevada</option>
                <option value="NY">New York</option>
                <option value="OH">Ohio</option>
                <option value="OK">Oklahoma</option>
                <option value="OR">Oregon</option>
                <option value="PA">Pennsylvania</option>
                <option value="PR">Puerto Rico</option>
                <option value="RI">Rhode Island</option>
                <option value="SC">South Carolina</option>
                <option value="SD">South Dakota</option>
                <option value="TN">Tennessee</option>
                <option value="TX">Texas</option>
                <option value="UT">Utah</option>
                <option value="VA">Virginia</option>
                <option value="VT">Vermont</option>
                <option value="WA">Washington</option>
                <option value="WI">Wisconsin</option>
                <option value="WV">West Virginia</option>
                <option value="WY">Wyoming</option>
              </select>
              <div class="invalid-feedback"> Please select your state </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-3">
              <label for="Zip5" class="form-label text-black-50">Zip code</label>
              <input class="form-control" name="Zip5" id="Zip5" required minlength="5" maxlength="5" placeholder="Zip code" autocomplete="postal-code"/>
              <div class="invalid-feedback"> Please fill zip-code 5 digits </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="submit-button-text" class="btn btn-primary btn-lg">Validate</button>
              <button type="button" id="submit-button-loading" class="btn btn-secondary btn-lg d-none" disabled >
                <span class="spinner-grow"></span> <span class="spinner-grow"></span>  <span class="spinner-grow"></span> 
              </button>
            </div>
          </div>
        </form>
      </section>
    </div>
    <!-- Display modal after submiting form -->
    <div class="modal" tabindex="-1" id="error-usps">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="error-title"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="error-message"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary"  data-bs-dismiss="modal" onclick="closeModals()">Close </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Display modal after submiting form -->
    <div class="modal" tabindex="-1" id="select-modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Save address</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <section>
              <div class="row">
                Witch address format do you want to save ? 
              </div>
              <div>
                <div class="btn-group" role="group" aria-label="Basic example">
                  <button type="button" id="format-user"class="btn btn-outline-primary" onclick="setSended(false);">Orginal</button>
                  <button type="button" id="format-usps"class="btn btn-outline-primary active" onclick="setSended(true)">Standardized(USPS)</button>
                </div>
              </div>
              <div id="selected-format-output" class="card mt-3 mb-3"></div>
              <div id="saved-status" class="p-3 border rounded-3"> </div>
            </section>
          </div>
          <div class="modal-footer">
            <button id="save-address-button" type="button" class="btn btn-primary" onclick="saveUserData()">Save </button>
          </div>
        </div>
      </div>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
  <script src="index.js"></script>
</body>
</html>