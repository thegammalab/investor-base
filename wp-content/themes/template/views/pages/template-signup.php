<?php
/*
Template Name: Signup Page
*/
?>
<div class="container">
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#signup">
    sign up
  </button>

  <!-- Modal -->
  <div class="modal fade" id="signup" tabindex="-1" role="dialog" aria-labelledby="signupLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg signup_modal" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="signupLabel">Sign In</h2>

        </div>
        <div class="modal-body">
          <h4>sign in with your social accounts:</h4>
          <div class="social_login d-flex">
            <a href="#" class="fb_blue flex-fill">Facebook</a>
            <a href="#" class="google_red flex-fill">Google </a>
            <a href="#" class="twitter_blue flex-fill">Twitter</a>
          </div>

          <h4>login to your account</h4>
          <form class="signup_form">
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-group">
                  <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="First Name...">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Last Name...">
                </div>
              </div>
            </div>
            <div class="row align-items-center">
              <div class="col-md-6">
                <a href="#" class="forgot_pass">Forgot your Password?</a>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary w-100">Register your account</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#register">
    register
  </button>

  <!-- Modal -->
  <div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="registerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg signup_modal" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="registerLabel">Register</h2>

        </div>
        <div class="modal-body">
          <ul class="nav-justified d-flex align-items-center" id="register_form" role="tablist">
            <li>
              <a class="active" href="#" >basic info </a>
            </li>
            <li >
              <a class="" href="#">profile info  </a>
            </li>
            <li class="">
              <a class="" href="#">watchlist </a>
            </li>
            <li class="">
              <a class="" href="#">my interests </a>
            </li>
            <li class="">
              <a class="" href="#"> invite friends</a>
            </li>
          </ul>
          <h4>sign in with your social accounts:</h4>
          <div class="social_login d-flex">
            <a href="#" class="fb_blue flex-fill">Facebook</a>
            <a href="#" class="google_red flex-fill">Google </a>
            <a href="#" class="twitter_blue flex-fill">Twitter</a>
          </div>
          <hr class="mt-0 mb-4">
          <h4>login to your account</h4>
          <form class="signup_form">
            <div class="row ">
              <div class="col-md-6">
                <div class="form-group">
                  <input type="text" class="form-control" id="firstname" aria-describedby="emailHelp" placeholder="First Name...">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <input type="text" class="form-control" id="lastname" placeholder="Last Name...">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <input type="password" class="form-control" id="pass" placeholder="Password...">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <input type="password" class="form-control" id="confirmpass" placeholder="Confirm Password...">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <input type="text" class="form-control" id="email" placeholder="Your email address...">
                </div>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary w-100">Register your account</button>
              </div>
            </div>

          </form>
        </div>

      </div>
    </div>
  </div>

  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#profile">
    Profile tab
  </button>

  <!-- Modal -->
  <div class="modal fade" id="profile" tabindex="-1" role="dialog" aria-labelledby="profileLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg signup_modal" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="profileLabel">Register</h2>

        </div>
        <div class="modal-body">
          <ul class=" nav-justified d-flex align-items-center" id="register_form" role="tablist">
            <li class="">
              <a class=" active" href="#">basic info </a>
            </li>
            <li class="" href="#">
              <a class=" active" >profile info  </a>
            </li>
            <li class="">
              <a class="" href="#">watchlist </a>
            </li>
            <li class="">
              <a class="" href="#">my interests </a>
            </li>
            <li class="">
              <a class="" href="#"> invite friends</a>
            </li>
          </ul>

          <h4>we would like to know a bit more</h4>
          <form class="signup_form">
            <div class="row ">
              <div class="col-md-12">
                <div class="form-group">
                  <textarea class="form-control" id="exampleFormControlTextarea1" rows="10" placeholder="Tell us a bit about yourself..."></textarea>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <input type="text" class="form-control" id="lastname" placeholder="Upload an image of yourself">
                </div>
              </div>


            </div>
            <div class="row align-items-center">
              <div class="col-md-6">
                <a href="#" class="forgot_pass">Skip this step</a>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary w-100">save my profile information</button>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>


  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#watchlist">
    Watchlist tab
  </button>

  <!-- Modal -->
  <div class="modal fade" id="watchlist" tabindex="-1" role="dialog" aria-labelledby="watchlistLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg signup_modal" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="watchlistLabel">Register</h2>

        </div>
        <div class="modal-body">
          <ul class=" nav-justified d-flex align-items-center" id="register_form" role="tablist">
            <li class="">
              <a class=" active" href="#">basic info </a>
            </li>
            <li class="">
              <a class=" active" href="#">profile info  </a>
            </li>
            <li class="">
              <a class=" active" href="#">watchlist </a>
            </li>
            <li class="">
              <a class="" href="#">my interests </a>
            </li>
            <li class="">
              <a class="" href="#"> invite friends</a>
            </li>
          </ul>

          <h4>start building your watchlist</h4>
          <form class="signup_form">
            <div class="row ">
              <div class="col-md-12">
                <div class="search_input">
                  <input type="text" class="form-control" placeholder="Start typing in company names...">
                  <button class="btn">Search</button>
                </div>

              </div>
            </div>
            <table class="table watchlist_tab">
              <thead>
                <tr>
                  <th scope="col">Symbol</th>
                  <th scope="col"></th>
                  <th scope="col">Last price</th>
                  <th scope="col">% change</th>
                  <th scope="col">change</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">Loxo </th>
                  <th scope="row">Loxo </th>
                  <td>122.53</td>
                  <td class="green">+10.08</td>
                  <td class="green">+10.08</td>
                </tr>
                <tr>
                  <th scope="row">LEN </th>
                  <th scope="row">Lennar corporation</th>
                  <td>62.82</td>
                  <td class="green">+10.04%</td>
                  <td class="green">+10.04%</td>
                </tr>

              </tbody>
            </table>
            <div class="row align-items-center">
              <div class="col-md-6">
                <a href="#" class="forgot_pass">Skip this step</a>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary w-100">save my watchlist</button>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>


  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Interest">
    Interest tab
  </button>

  <!-- Modal -->
  <div class="modal fade" id="Interest" tabindex="-1" role="dialog" aria-labelledby="InterestLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg signup_modal" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="InterestLabel">Register</h2>

        </div>
        <div class="modal-body">
          <ul class=" nav-justified d-flex align-items-center" id="register_form" role="tablist">
            <li class="">
              <a class=" active" href="#">basic info </a>
            </li>
            <li class="">
              <a class=" active" href="#">profile info  </a>
            </li>
            <li class="">
              <a class=" active" href="#">watchlist </a>
            </li>
            <li class="">
              <a class=" active" href="#">my interests </a>
            </li>
            <li class="">
              <a class="" href="#"> invite friends</a>
            </li>
          </ul>

          <h4>tell us about what you are interested in</h4>
          <form class="signup_form">
            <div class="row ">
              <div class="col-md-6">
                <div class="input-group mb-3 ">
                  <div class="input-group-text">
                    <input type="checkbox" aria-label="Checkbox for following text input" >Lorem ipsum dolor sit amet
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input-group mb-3 ">
                  <div class="input-group-text">
                    <input type="checkbox" aria-label="Checkbox for following text input" >Lorem ipsum dolor sit amet
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input-group mb-3 ">
                  <div class="input-group-text">
                    <input type="checkbox" aria-label="Checkbox for following text input" >Lorem ipsum dolor sit amet
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input-group mb-3 ">
                  <div class="input-group-text">
                    <input type="checkbox" aria-label="Checkbox for following text input" >Lorem ipsum dolor sit amet
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input-group mb-3 ">
                  <div class="input-group-text">
                    <input type="checkbox" aria-label="Checkbox for following text input" >Lorem ipsum dolor sit amet
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input-group mb-3 ">
                  <div class="input-group-text">
                    <input type="checkbox" aria-label="Checkbox for following text input" >Lorem ipsum dolor sit amet
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input-group mb-3 ">
                  <div class="input-group-text">
                    <input type="checkbox" aria-label="Checkbox for following text input" >Lorem ipsum dolor sit amet
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input-group mb-3 ">
                  <div class="input-group-text">
                    <input type="checkbox" aria-label="Checkbox for following text input" >Lorem ipsum dolor sit amet
                  </div>
                </div>
              </div>
            </div>

            <div class="row align-items-center">
              <div class="col-md-6">
                <a href="#" class="forgot_pass">Skip this step</a>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary w-100">save my interests</button>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>


  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#invite">
    invite friends tab
  </button>

  <!-- Modal -->
  <div class="modal fade" id="invite" tabindex="-1" role="dialog" aria-labelledby="inviteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg signup_modal" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="inviteLabel">Register</h2>

        </div>
        <div class="modal-body">
          <ul class=" nav-justified d-flex align-items-center" id="register_form" role="tablist">
            <li class="">
              <a class=" active" href="#">basic info </a>
            </li>
            <li class="">
              <a class=" active" href="#">profile info  </a>
            </li>
            <li class="">
              <a class=" active" href="#">watchlist </a>
            </li>
            <li class="">
              <a class=" active" href="#">my interests </a>
            </li>
            <li class="">
              <a class="active" href="#"> invite friends</a>
            </li>
          </ul>

          <h4>tell your friends about it</h4>
          <form class="signup_form">
            <div class="row ">
              <div class="col-md-12 mb-4">
                <div class="search_input">
                  <input type="text" class="form-control" placeholder="Friend's email...">
                  <button class="btn w-25 text-right">Send invite</button>
                </div>
              </div>
            </div>
            <ul class="invites_sent">
              <li>Jerry.Holway@outlook.com <span>(sent)</span></li>
              <li>Jerry.Holway@outlook.com <span>(sent)</span></li>
              <li>Jerry.Holway@outlook.com <span>(sent)</span></li>
            </ul>

            <div class="row align-items-center">
              <div class="col-md-6">
                <a href="#" class="forgot_pass">Skip this step</a>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary w-100">finish signup</button>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>
