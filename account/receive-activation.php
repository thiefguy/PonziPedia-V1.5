<?php include '_header.php';

if (empty($_GET['u'])) redirect_to(App::url('account/index.php'));
$user = DB::table('activationFee')->where('id', $_GET['u'])->first();
?>
<?php if (is_null($user)) redirect_to(App::url('account/index.php')); ?>
<?php  

$Validmaching = DB::table('activationFee')->where('receiver_id', $user_id)->where('id', $_GET['u'])->first(); 
if (is_null($Validmaching)) redirect_to(App::url('account/index.php'));


  $ProfileCh = DB::table('userdetails')->where('userid', $user_id)->first();
     if (is_null($ProfileCh)) redirect_to(App::url('account/account.php'));
  
  $ProfileReceiver = DB::table('userdetails')->where('userid', $Validmaching->sender_id)->first();
  $userReceiver = DB::table('users')->where('id', $Validmaching->receiver_id)->first();
  $sender_userid = $Validmaching->sender_id;

if (isset($_POST['submitTest'])) {
  Session::set('Testimony', 'yes');
  ?>

  <script>window.location.href ='<?php echo Config::get('app.telegram'); ?>';</script>
  <?php
}

?>


 <div class="content-inner">
          <!-- Page Header-->
          <header class="page-header">
            <div class="container-fluid">
              <h2 class="no-margin-bottom">Confirm Activation Fees Payment - Pack
            (<?php echo $settings->currency; ?> <?php echo $Validmaching->amount; ?>)</h2>
            </div>
          </header>
    
    <!-- Content Header (Page header) -->
   
    <!-- Main content -->
          <!-- Dashboard Counts Section-->
          <section class="dashboard-counts no-padding-bottom">
            <div class="container-fluid">
               <?php if (isset($_POST['confirmUserActivation'])){
                $id =$_POST['id'];
                     confirmUserActivationPay($user_id, $sender_userid, $id);
                }
             ?>

              <?php if (isset($_POST['ActivationReport'])){
                    $id =$_POST['id']; 
                     ReportUserActivation($user_id, $sender_userid, $id);
                }
             ?>
              <div class="row">
           <div class="col-lg-6">
                  <div class="card">
                    <div class="card-close">
                      <div class="dropdown">
                        <button type="button" id="closeCard1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle"><i class="fa fa-ellipsis-v"></i></button>
                        <div aria-labelledby="closeCard1" class="dropdown-menu dropdown-menu-right has-shadow">
                            <a href="support.php" class="dropdown-item edit"> <i class="fa fa-envelope"></i>Get Support</a></div>
                      </div>
                    </div>
                    <div class="card-header d-flex align-items-center">
                      <h3 class="h4">Sender Profile</h3>
                    </div>
                    <div class="card-body" style="padding-left: 3px;padding-right: 0px;">
                       <center><img class="img-fluid rounded-circle" src="<?php echo $ProfileReceiver->avater; ?>" style="width: 80px;" alt="User profile picture"></center>

                        <h3 class="profile-username text-center"><?php echo $ProfileReceiver->accountname;  ?></h3>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <i class="fa fa-user"></i> <a class="pull-right"><?php echo $ProfileReceiver->accountname;  ?></a>
                            </li>
                                         
                            <div class="box box-success">

                                <div class="box-header with-border">
                                                                            <h3 class="box-title">Default Bank Account</h3>
                                                                    </div>
                                <div class="box-body">
                                    
                                        <ul class="list-group list-group-unbordered">
                                            <li class="list-group-item">
                                                <i class="fa fa-bank"></i> Bank Name <a class="pull-right"><?php echo $ProfileReceiver->bankname;  ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-bank"></i> Account Name <a class="pull-right"><?php echo $ProfileReceiver->accountname;  ?></a>
                                            </li>
                                            <li class="list-group-item">
                                                <i class="fa fa-bank"></i> Account Number <a class="pull-right"><?php echo $ProfileReceiver->accountnumber;  ?></a>
                                            </li>

                                            <li class="list-group-item">
                                                <i class="fa fa-bank"></i> Account Type <a class="pull-right"><?php echo $ProfileReceiver->accounttype;  ?></a>
                                            </li>
                                        </ul>
                    </div>
                  </div>
                </div>


                
              
            </div>
        </ul>
    </div>


  <!-- Line Chart            -->
                <div class="chart col-lg-6 col-12">

                  <?php 

                  $CheckingMarching = DB::table('activationFee')->where('receiver_id', $user_id)->where('id', $user->id)->first(); 
                        $timeCreated = $CheckingMarching->expiringTime;
                        $timeNow = date('Y-m-d H:i:s');

                   $userBlocked = DB::table('users')->where('id', $ProfileReceiver->userid)->first();
                   if ($userBlocked->status == 2) {
                    echo '
                    <div class="card-body text-center" style="background-color: #dc3545; color: #fff;"><h3>User with this account is blocked, possible time elapse or violate our terms, please contact support here incase you need help <a href="support.php">Contact Us</a> </h3></div>';
                  
                   }
                   else{
                  if ($CheckingMarching->payment_status == "pending") {
                   echo '
                    <div class="card-body text-center" style="background-color: #218838; color: #fff;"><h3>Payment is still pending and waiting for sender to make payment</h3></div>';
                  }
                   if ($CheckingMarching->payment_status == "waiting") {
                   echo '
                    <div class="card-body text-center" style="background-color: #218838; color: #fff;"><h3>Please check below information and confirm user payment</h3></div>';
                  }
                   if ($CheckingMarching->payment_status == "pending" || $CheckingMarching->payment_status == "waiting") {
                  ?>
                

                <div class="work-amount card">
                   
                    <div class="card-body" style="padding-left: 3px;padding-right: 0px;">

                    
                    
                  
                     <ul class="list-group list-group-unbordered">
                      <?php if ($CheckingMarching->paymentMethod !=""): ?>
                       
                   <li class="list-group-item">
                    Sender's Method: <a class="pull-right"><?php echo $CheckingMarching->paymentMethod;  ?></a>
                     </li>
                       <?php endif ?>

                       <?php if ($CheckingMarching->senderBank !=""): ?>
                   <li class="list-group-item">
                    Sender's Bank Bame: <a class="pull-right"><?php echo $CheckingMarching->senderBank;  ?></a>
                     </li>
                     <?php endif ?>


                       <?php if ($CheckingMarching->accountNumber !=""): ?>
                   <li class="list-group-item">
                    Sender's Account Number: <a class="pull-right"><?php echo $CheckingMarching->accountNumber;  ?></a>
                     </li>
                      <?php endif ?>

                       <?php if ($CheckingMarching->AccountName !=""): ?>
                   <li class="list-group-item">
                    Sender's Account Name: <a class="pull-right"><?php echo $CheckingMarching->AccountName;  ?></a>
                     </li>
                     <?php endif ?>

                       <?php if ($CheckingMarching->depositorsName !=""): ?>
                   <li class="list-group-item">
                    Sender's Depositor Name: <a class="pull-right"><?php echo $CheckingMarching->depositorsName;  ?></a>
                     </li>
                     <?php endif ?>

                       <?php if ($CheckingMarching->paymentLocation !="") : ?>
                   <li class="list-group-item">
                    Sender's Location: <a class="pull-right"><?php echo $CheckingMarching->paymentLocation;  ?></a>
                     </li>
                     <?php endif ?>

                      <?php if ($CheckingMarching->ProofPic !=""): ?>
                   <li class="list-group-item">
                    <a href="<?php echo App::url(); ?>/images/<?php echo $CheckingMarching->ProofPic;  ?>" target="_BLANK" class="pull-right"><img src="<?php echo App::url(); ?>/images/<?php echo $CheckingMarching->ProofPic;  ?>" alt="<?php echo $CheckingMarching->ProofPic;  ?>" class="img-responsive" style="width: 90%;"></a>
                     </li>
                     <?php endif ?>
                    </ul>

                    <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $CheckingMarching->id; ?>">
                        <div class="form-group row" style="padding: 5px 15px;padding-left: 5px;padding-right: 5px;">       
                          
                            <input type="submit" name="confirmUserActivation" value="Confirm Payment" class="btn btn-primary btn-lg btn-block" style="margin-bottom: 5px;">
                          </div>
                    </form>
                    <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo $CheckingMarching->id; ?>">
                          <div class="form-group row" style="padding: 5px 15px;padding-left: 5px;padding-right: 5px;">       
                          
                            <input type="submit" value="Report Fake Pop!" name="ActivationReport" class="btn btn-danger btn-lg btn-block" style="margin-bottom: 5px;">
                          
                        </div>
                      </form>
                      </form>
                    </div>
                  </div>
             
             
              </div>
                    <?php
                  }
                 
                   elseif ($CheckingMarching->payment_status == "confirm") {
                    echo '  <div class="card-body text-center" style="background-color: #218838; color: #fff;"><h3>Welldone! you have successfully confirm this payment</h3></div>';
                  }else{
                     echo '  <div class="card-body text-center" style="background-color: #dc3545; color: #fff;"><h3>This payment is not understood, please contact support for possible assistant</h3></div>';
                    }
                  }
                



                    ?>
            </div>
                  
          </section>


    <?php include '_footer.php'; ?>
