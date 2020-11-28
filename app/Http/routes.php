<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */
#----------------------- ADMIN ---------------------------#
Route::get('/admin', 'AdminController@index');
Route::post('/login', 'AdminController@login');
Route::get('/adminLogout', 'AdminController@adminLogout');
Route::get('/addAdminOpeningBalance', 'AdminController@addAdminOpeningBalance');
Route::get('/adminDashboard', 'DashboardController@adminDashboard');
Route::post('/addAdminOpeningBalanceInfo', 'AdminController@addAdminOpeningBalanceInfo');
// manager dashboard
Route::get('/managerDashboard', 'DashboardController@managerDashboard');
// cassier opening petycash balance
Route::get('/managerAddOpeningCashierBalance', 'DashboardController@managerAddOpeningCashierBalance');
Route::post('/addCashierOpeningBalanceInfo', 'DashboardController@addCashierOpeningBalanceInfo');
// manager logout
Route::get('/managerLogout', 'AdminController@managerLogout');
// cashier dashborad
Route::get('/cashierDashboard', 'DashboardController@cashierDashboard');
Route::get('/cashierLogout', 'AdminController@cashierLogout');
// doctor dashboard
Route::get('/doctorDashboard', 'DashboardController@doctorDashboard');
#----------------------- BRANCH ---------------------------#
Route::get('/addBranch','BranchController@addBranch');
Route::post('/addBrancInfo','BranchController@addBrancInfo');
Route::get('/manageBranch','BranchController@manageBranch');
#----------------------- END BRANCH -----------------------#
#----------------------- MANAGER---------------------------#
Route::get('/addBranchManager','AdminController@addBranchManager');
Route::post('/addManagerInfo','AdminController@addManagerInfo');
Route::get('/manageBranchManager','AdminController@manageBranchManager');
#---------------------- END MANAGER------------------------#
#---------------------- BUILDING --------------------------#
Route::get('/addBuilding','BuildingController@addBuilding');
Route::post('/addBuildingInfo','BuildingController@addBuildingInfo');
Route::get('/manageBuilding','BuildingController@manageBuilding');
// get building info while ipd admission
Route::post('/getAvailableRoomForIpdAdmission','BuildingController@getAvailableRoomForIpdAdmission');
Route::post('/getAvailableWardForIpdAdmission','BuildingController@getAvailableWardForIpdAdmission');
#---------------------- END BUILDING ----------------------#
#---------------------- FLOOR -----------------------------#
Route::get('/addBuildingFloor','BuildingController@addBuildingFloor');
Route::post('/addBuildingFloorInfo','BuildingController@addBuildingFloorInfo');
Route::get('/manageBuildingFloor','BuildingController@manageBuildingFloor');
#--------------------- END FLOOR --------------------------#
#--------------------- CABIN ------------------------------#
Route::get('/addCabinType','BuildingController@addCabinType');
Route::post('/addCabinTypeInfo','BuildingController@addCabinTypeInfo');
Route::get('/manageCabinType','BuildingController@manageCabinType');
Route::get('/addCabinRoom','BuildingController@addCabinRoom');
Route::post('/addCabinRoomInfo','BuildingController@addCabinRoomInfo');
// get floor by building id
Route::post('/getFloorByBuildingId','BuildingController@getFloorByBuildingId');
Route::get('/manageCabinRoom','BuildingController@manageCabinRoom');
#-------------------- END CABIN ---------------------------#	
#-------------------- WARD --------------------------------#
Route::get('/addWard','BuildingController@addWard');
Route::post('/addWardInfo','BuildingController@addWardInfo');
Route::get('/manageWard','BuildingController@manageWard');
#------------------- END WARD -----------------------------#
#---------------------- CASSIER ---------------------------#
Route::get('/addCashier','UserController@addCashier');
Route::post('/addCashierInfo','UserController@addCashierInfo');
Route::get('/manageCashier','UserController@manageCashier');
#---------------------- END CASSIER------------------------#
#---------------------- DOCTOR-----------------------------#
Route::get('/addDoctorByCashier','UserController@addDoctorByCashier');
Route::post('/addDoctorInfo','UserController@addDoctorInfo');
Route::get('/manageDoctorByCashier','UserController@manageDoctorByCashier');
#---------------------- END DOCTOR-------------------------# 
#---------------------- OT STAFF -----------------------------#
Route::get('/addOTStaff','UserController@addOTStaff');
Route::post('/addOTstaffInfo','UserController@addOTstaffInfo');
Route::get('/manageOTStaff','UserController@manageOTStaff');
#--------------------- END OT STAFF---------------------------#
#---------------------- PC --------------------------------#
Route::get('/addPCByCashier','UserController@addPCByCashier');
Route::post('/addPCInfo','UserController@addPCInfo');
Route::get('/managePcByCashier','UserController@managePcByCashier');
#---------------------- END PC ----------------------------#
#----------------------- UNIT -----------------------------#
Route::get('/addUnit','UnitController@addUnit');
Route::post('/addUnitInfo','UnitController@addUnitInfo');
Route::get('/manageUnit','UnitController@manageUnit');
#-----------------------END UNIT -----------------------#
#----------------------- PRODUCT------------------------#
#----------------------- PRODUCT --------------------------#
Route::get('/addProduct','ProductController@addProduct');
Route::post('/addProductInfo','ProductController@addProductInfo');
Route::get('/manageProduct','ProductController@manageProduct');
Route::post('/getProductPurchasePrice','ProductController@getProductPurchasePrice');
#----------------------- END PRODUCT-----------------------#
#---------------------- TEST ------------------------------#
Route::get('/addMedicalTest','TestController@addMedicalTest');
Route::post('/addTestInfo','TestController@addTestInfo');
Route::get('/manageMedicalTest','TestController@manageMedicalTest');
// get  test price to create pathology bill
Route::post('/getTestPrice','TestController@getTestPrice');
#--------------------- END TEST ---------------------------#
#----------------------- EXPENSE ----------------------------------------#
Route::get('/addExpenseCategory','ExpenseController@addExpenseCategory');
Route::post('/addExpenseCategoryInfo','ExpenseController@addExpenseCategoryInfo');
Route::get('/manageExpenseCategory','ExpenseController@manageExpenseCategory');
// make expense
Route::get('/addManagerExpense','ExpenseController@addManagerExpense');
Route::post('/addManagerExpenseInfo','ExpenseController@addManagerExpenseInfo');
#----------------------- END EXPENSE--------------------------------------#
#----------------------- SUPPLIER --------------------------#
Route::get('/addSupplier','SupplierController@addSupplier');
Route::post('/addSupplierInfo','SupplierController@addSupplierInfo');
Route::get('/manageSupplier','SupplierController@manageSupplier');
#-----------------------END SUPPLIER -----------------------#
#----------------------- PURCHASE BILL --------------------------#
Route::get('/addPurchase','PurchaseController@addPurchase');
Route::post('/createPurchaseBill','PurchaseController@createPurchaseBill');
Route::get('/managePurchase','PurchaseController@managePurchase');
#-----------------------END PURCHASE BILL -----------------------#
#------------------------- BANK ------------------------------#
Route::get('/addBank','BankController@addBank');
Route::post('/addBankInfo','BankController@addBankInfo');
Route::get('/manageBank','BankController@manageBank');
// c2b
Route::get('/cashToBankTransaction','BankController@cashToBankTransaction');
Route::post('/cashToBankTransactionInfo','BankController@cashToBankTransactionInfo');
// b2c
Route::get('/bankToCashTransaction','BankController@bankToCashTransaction');
Route::post('/getBankAmount','BankController@getBankAmount');
Route::post('/bankToCashTransactionInfo','BankController@bankToCashTransactionInfo');
#------------------------- END BANK --------------------------#
#------------------------- SUPPLIER PAYMENT----------------------------#
Route::get('/supplierPayment','PaymentController@supplierPayment');
Route::post('/getSupllierDueAmount','PaymentController@getSupllierDueAmount');
Route::post('/supplierPaymentAmt','PaymentController@supplierPaymentAmt');
Route::get('/managePayment','PaymentController@managePayment');
#------------------------- END SUPPLIER PAYMENT---------------------------#
#------------------------- PC PAYMENT ------------------------------------#
Route::get('/pcPayment','PaymentController@pcPayment');
// get pc due amount
Route::post('/getPcDueAmount','PaymentController@getPcDueAmount');
Route::post('/pcPaymentAmt','PaymentController@pcPaymentAmt');
#------------------------- END PC PAYMENT ---------------------------------#
#------------------------- OPD -------------------------------------------#
Route::get('/addOpdCategory','OpdController@addOpdCategory');
Route::post('/addOPDCategoryInfo','OpdController@addOPDCategoryInfo');
Route::get('/manageOpdCategory','OpdController@manageOpdCategory');
Route::get('/addOpdFee','OpdController@addOpdFee');
Route::post('/addOpdFeeAmt','OpdController@addOpdFeeAmt');
Route::get('/manageOpdFee','OpdController@manageOpdFee');
#------------------------- END OPD ---------------------------------------#
#------------------------ BILL -------------------------------------------#
Route::get('/collectBill','BillController@collectBill');
// pathology bill
Route::get('/pathologyBillCreate','BillController@pathologyBillCreate');
Route::post('/createPathologyBill','BillController@createPathologyBill');
// opd bill
Route::get('/opdBillCreate','BillController@opdBillCreate');
Route::post('/getOpdCategoryOfThisDoctor','OpdController@getOpdCategoryOfThisDoctor');
Route::post('/getOpdFeePrice','OpdController@getOpdFeePrice');
Route::post('/createOpdBill','BillController@createOpdBill');
// manage bill
Route::get('/managePathlogyBill','BillController@managePathlogyBill');
Route::get('/manageOpdBill','BillController@manageOpdBill');
#------------------------ END BILL ---------------------------------------#
#------------------------ DUE BILL COLLLECTION ---------------------------#
Route::get('/pathlogyBillDueCollect','DueCollectController@pathlogyBillDueCollect');
Route::post('/getInvoiceInfo','DueCollectController@getInvoiceInfo');
Route::post('/getInvoiceCalculation','DueCollectController@getInvoiceCalculation');
Route::post('/pathologyDueCollectInfo','DueCollectController@pathologyDueCollectInfo');
Route::get('/pathlogyBillDueCollectList','DueCollectController@pathlogyBillDueCollectList');
// opd bill due collection
Route::get('/opdBillDueCollect','DueCollectController@opdBillDueCollect');
Route::post('/getOPDInvoiceInfo','DueCollectController@getOPDInvoiceInfo');
Route::post('/getOpdInvoiceCalculation','DueCollectController@getOpdInvoiceCalculation');
Route::post('/opdDueCollectInfo','DueCollectController@opdDueCollectInfo');
Route::get('/opdBillDueCollectList','DueCollectController@opdBillDueCollectList');
#------------------------ END DUE BILL COLLECTION ------------------------#
#------------------------ IPD START --------------------------------------#
Route::get('/ipdAdmission','IpdController@ipdAdmission');
Route::get('/addIPDAdmissinFeeAmount','IpdController@addIPDAdmissinFeeAmount');
Route::post('/addIpdAdmissionFee','IpdController@addIpdAdmissionFee');
Route::get('/manageIPDAdmissinFeeAmount','IpdController@manageIPDAdmissinFeeAmount');
// ipd service
Route::get('/addIPDService','IpdController@addIPDService');
Route::post('/addIpdServiceInfo','IpdController@addIpdServiceInfo');
Route::get('/manageIPDService','IpdController@manageIPDService');
Route::get('/ipdBillClearence','IpdController@ipdBillClearence');
Route::post('/getIPDServicePrice','IpdController@getIPDServicePrice');
Route::get('/ipdServiceBill','IpdController@ipdServiceBill');
// ipd admission by cassier
Route::post('/patientIpdAdmission','IpdController@patientIpdAdmission');
Route::get('/ipdPathologyBillCreate','IpdController@ipdPathologyBillCreate');
// get all booking cabin room
Route::post('/getAllBokkingCabinRoom','IpdController@getAllBokkingCabinRoom');
Route::post('/getAllBokkingBedRoom','IpdController@getAllBokkingBedRoom');
// get patient info
Route::post('/getCabinRoomPatientInfoForAddIpdPathology','IpdController@getCabinRoomPatientInfoForAddIpdPathology');
Route::post('/getWardBedPatientInfoForAddIpdPathology','IpdController@getWardBedPatientInfoForAddIpdPathology');
Route::post('/createIPDPathologyBill','IpdController@createIPDPathologyBill');
// create ipd service bill
Route::post('/createIPDServiceBill','IpdController@createIPDServiceBill');
// ipd clearence first step
Route::post('/ipdClearenceLedgerPayment','IpdController@ipdClearenceLedgerPayment');
// ipd clearence bill
Route::post('/createIpdClearenceBill','IpdController@createIpdClearenceBill');
#------------------------ END IPD START-----------------------------------#
#------------------------ START OT ---------------------------------------#
Route::get('/addOTRoom','OtController@addOTRoom');
Route::post('/addOTRoomInfo','OtController@addOTRoomInfo');
Route::get('/manageOTRoom','OtController@manageOTRoom');

Route::get('/addOTtype','OtController@addOTtype');
Route::post('/addOTtypeInfo','OtController@addOTtypeInfo');
Route::get('/manageOTtype','OtController@manageOTtype');
// add ot service
Route::get('/addOTService','OtController@addOTService');
Route::post('/addOTServiceInfo','OtController@addOTServiceInfo');
Route::get('/manageOTService','OtController@manageOTService');
// ot booking
Route::get('/OTBooking','OtController@OTBooking');
Route::post('/patientOTAdmission','OtController@patientOTAdmission');
Route::get('/OTSurgeryClinicalPosting','OtController@OTSurgeryClinicalPosting');
Route::post('/patientOTSuergeryStaffBill','OtController@patientOTSuergeryStaffBill');
Route::get('/OTserviceBill','OtController@OTserviceBill');
Route::post('/getOTServicePrice','OtController@getOTServicePrice');
Route::post('/createOTServiceBill','OtController@createOTServiceBill');
Route::get('/OTBillClearence','OtController@OTBillClearence');
Route::post('/otClearenceLedgerPayment','OtController@otClearenceLedgerPayment');
Route::post('/createOTClearenceBill','OtController@createOTClearenceBill');
Route::get('/OTamountDistribution','OtController@OTamountDistribution');
Route::get('/otStaffAmountDistribution/{booking_id}/{patient_id}','OtController@otStaffAmountDistribution');
Route::post('/patientOTSuergeryStaffBillDistribution','OtController@patientOTSuergeryStaffBillDistribution');
// OT payment
Route::get('/otPayment','OtController@otPayment');
Route::post('/getStaffForOTPayment','OtController@getStaffForOTPayment');
Route::post('/getOTpaymentLedger','OtController@getOTpaymentLedger');
Route::get('/otStaffPayment/{booking_id}/{staff_type}/{staff_id}/{patient_id}','OtController@otStaffPayment');
Route::post('/otPaymentAmt','OtController@otPaymentAmt');
#------------------------ END OT -----------------------------------------#
#------------------------ START HR ---------------------------------------#
Route::get('/addDesignation','HrController@addDesignation');
Route::post('/addDesignationInfo','HrController@addDesignationInfo');
Route::get('/manageDesignation','HrController@manageDesignation');
Route::get('/addEmp','HrController@addEmp');
Route::post('/addEmpInfo','HrController@addEmpInfo');
Route::get('/manageEmp','HrController@manageEmp');
Route::get('/empSalaryInfo/{id}','HrController@empSalaryInfo');
#------------------------ END HR -----------------------------------------#
#------------------------ START SALARY -----------------------------------#
Route::get('/manageSalary','SalaryController@manageSalary');
Route::post('/changeSalary','SalaryController@changeSalary');
Route::get('/salaryPaymentForm','SalaryController@salaryPaymentForm');
Route::post('/getSalaryCalculation','SalaryController@getSalaryCalculation');
Route::post('/paymentSalary','SalaryController@paymentSalary');
#------------------------ END SALARY -------------------------------------#
#------------------------ BALANCE TRANSFER -------------------------------#
Route::get('/cashierBalanceTransfer','BalanceTransfer@cashierBalanceTransfer');
Route::post('/cashierCashTransferToAdmin','BalanceTransfer@cashierCashTransferToAdmin');
Route::get('/cashierBalanceTransferReceiveByManager','BalanceTransfer@cashierBalanceTransferReceiveByManager');
Route::get('/managerApprovedBalanceTransfer/{id}','BalanceTransfer@managerApprovedBalanceTransfer');
Route::get('/managerRejectBalanceTransfer/{id}','BalanceTransfer@managerRejectBalanceTransfer');
#------------------------ END BALANCE TRANSFER ---------------------------#
#-------------------------- LEADGER -------------------------------#
Route::get('/supplierLedger','LeadgerController@supplierLedger');
Route::get('/supplierFullLeadger/{id}','LeadgerController@supplierFullLeadger');
Route::get('/pcLedger','LeadgerController@pcLedger');
Route::get('/pcFullLeadger/{id}','LeadgerController@pcFullLeadger');
// cashier ledger
Route::get('/cashierCurrentIPDLedger','LeadgerController@cashierCurrentIPDLedger');
Route::post('/ipdPatientLedger','LeadgerController@ipdPatientLedger');
// cashier current ot ledger
Route::get('/cashierCurrentOTLedger','LeadgerController@cashierCurrentOTLedger');
Route::post('/otPatientLedger','LeadgerController@otPatientLedger');
#-------------------------- END LEADGER -------------------------------#
#-------------------------- DISCOUNT ----------------------------------#
Route::get('/cashierPathologyDoctorDiscount','DiscountController@cashierPathologyDoctorDiscount');
Route::post('/getInvoiceCalculationForPathologyDiscount','DiscountController@getInvoiceCalculationForPathologyDiscount');
Route::post('/pathologyDoctorDiscountInfo','DiscountController@pathologyDoctorDiscountInfo');
Route::get('/cashierOPDDoctorDiscount','DiscountController@cashierOPDDoctorDiscount');
Route::post('/getInvoiceCalculationForOPDDiscount','DiscountController@getInvoiceCalculationForOPDDiscount');
Route::post('/opdDoctorDiscountInfo','DiscountController@opdDoctorDiscountInfo');
#-------------------------- END DISCOUNT -------------------------------#
#------------------------- CASH BOOK -----------------------------------#
// manager  cashbook
Route::get('/managerFullCashbook','CashbookController@managerFullCashbook');
Route::get('/managerTodayCashbook','CashbookController@managerTodayCashbook');
Route::get('/managerDatewiseCashbook','CashbookController@managerDatewiseCashbook');
Route::post('/managerDatewiseCashbookView','CashbookController@managerDatewiseCashbookView');
// cashier cashbook
Route::get('/cashierFullCashbook','CashbookController@cashierFullCashbook');
Route::get('/cashierTodayCashbook','CashbookController@cashierTodayCashbook');
Route::get('/cashierDatewiseCashbook','CashbookController@cashierDatewiseCashbook');
Route::post('/cashierDatewiseCashbookView','CashbookController@cashierDatewiseCashbookView');
#------------------------ END CASH BOOK ---------------------------------#
#------------------------ BILL PRINT ------------------------------------#
Route::get('/cashierPreviousPathologyBillReportForPrint','BillPrintController@cashierPreviousPathologyBillReportForPrint');
Route::post('/cashierPathologyBillViewForPrint','BillPrintController@cashierPathologyBillViewForPrint');
Route::get('/cashierPreviousOPDBillReportForPrint','BillPrintController@cashierPreviousOPDBillReportForPrint');
Route::post('/cashierOPDBillViewForPrint','BillPrintController@cashierOPDBillViewForPrint');
Route::get('/cashierIPDAdmissionBillReportForPrint','BillPrintController@cashierIPDAdmissionBillReportForPrint');
Route::post('/cashierIpdAdmissionBillViewForPrint','BillPrintController@cashierIpdAdmissionBillViewForPrint');
Route::get('/cashierIPDPathologyBillReportForPrint','BillPrintController@cashierIPDPathologyBillReportForPrint');
Route::post('/cashierIpdPathologyBillViewForPrint','BillPrintController@cashierIpdPathologyBillViewForPrint');
Route::get('/cashierIPDServiceBillReportForPrint','BillPrintController@cashierIPDServiceBillReportForPrint');
Route::post('/cashierIpdServiceBillViewForPrint','BillPrintController@cashierIpdServiceBillViewForPrint');
Route::get('/cashierIPDClearanceBillReportForPrint','BillPrintController@cashierIPDClearanceBillReportForPrint');
Route::post('/cashierIpdClearBillViewForPrint','BillPrintController@cashierIpdClearBillViewForPrint');
Route::get('/cashierOTBookingBillReportForPrint','BillPrintController@cashierOTBookingBillReportForPrint');
Route::post('/cashierOTBookingBillViewForPrint','BillPrintController@cashierOTBookingBillViewForPrint');
Route::get('/cashierOTClearBillReportForPrint','BillPrintController@cashierOTClearBillReportForPrint');
Route::post('/cashierOTClearBillViewForPrint','BillPrintController@cashierOTClearBillViewForPrint');
// PC Change
Route::get('/cashierPathologyPcChange','BillPrintController@cashierPathologyPcChange');
Route::post('/cashierPathologyBillViewForPCChange','BillPrintController@cashierPathologyBillViewForPCChange');
Route::get('/pathologyPCChange/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}','BillPrintController@pathologyPCChange');
Route::post('/changePathologyPcAmountInfo','BillPrintController@changePathologyPcAmountInfo');
Route::get('/cashierIPDPcChange','BillPrintController@cashierIPDPcChange');
Route::post('/cashierIpdClearBillViewForPCChange','BillPrintController@cashierIpdClearBillViewForPCChange');
Route::get('/ipdPCChange/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}/{ipd_admission_id}','BillPrintController@ipdPCChange');
Route::post('/changeIPDPcAmountInfo','BillPrintController@changeIPDPcAmountInfo');
Route::get('/cashierOTPcChange','BillPrintController@cashierOTPcChange');
Route::post('/cashierOTClearBillViewForChangePC','BillPrintController@cashierOTClearBillViewForChangePC');
Route::get('/otPCChange/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}/{ot_booking_id}','BillPrintController@otPCChange');
Route::post('/changeOTPcAmountInfo','BillPrintController@changeOTPcAmountInfo');
#------------------------ END BILL PRINT --------------------------------#
#------------------------ START REPORT-----------------------------------#
// cashier report
Route::get('/cashierPathologyBillReport','ReportController@cashierPathologyBillReport');
Route::post('/cashierPathologyBillReportView','ReportController@cashierPathologyBillReportView');
Route::get('/cashierOPDBillReport','ReportController@cashierOPDBillReport');
Route::post('/cashierOPDBillReportView','ReportController@cashierOPDBillReportView');
Route::get('/cashierIpdAdmissionBillReport','ReportController@cashierIpdAdmissionBillReport');
Route::post('/cashierIpdAdmissionBillReportView','ReportController@cashierIpdAdmissionBillReportView');
Route::get('/cashierIpdPathologyBillReport','ReportController@cashierIpdPathologyBillReport');
Route::post('/cashierIpdPathologyBillReportView','ReportController@cashierIpdPathologyBillReportView');
Route::get('/cashierIpdServiceBillReport','ReportController@cashierIpdServiceBillReport');
Route::post('/cashierIpdServiceBillReportView','ReportController@cashierIpdServiceBillReportView');
Route::get('/cashierIpdClearanceBillReport','ReportController@cashierIpdClearanceBillReport');
Route::post('/cashierIpdClearanceBillReportView','ReportController@cashierIpdClearanceBillReportView');
Route::get('/cashierOTBookingBillReport','ReportController@cashierOTBookingBillReport');
Route::post('/cashierOTBookingBillReportView','ReportController@cashierOTBookingBillReportView');
Route::get('/cashierOTClearanceBillReport','ReportController@cashierOTClearanceBillReport');
Route::post('/cashierOTClearnceBillReportView','ReportController@cashierOTClearnceBillReportView');
Route::get('/cashierCashTransferReport','ReportController@cashierCashTransferReport');
Route::post('/cashierCashTransferReportView','ReportController@cashierCashTransferReportView');
Route::get('/cashierOTSurjenBillReport','ReportController@cashierOTSurjenBillReport');
Route::post('/cashierOTSurjeonAndStaffBillReportView','ReportController@cashierOTSurjeonAndStaffBillReportView');
Route::get('/cashierOTServiceBillReport','ReportController@cashierOTServiceBillReport');
Route::post('/cashierOTServiceBillReportView','ReportController@cashierOTServiceBillReportView');
// manager report
Route::get('/managerPurchaseReport','ReportController@managerPurchaseReport');
Route::post('/managerPurchaseReportView','ReportController@managerPurchaseReportView');
Route::get('/managerSupplierPaymentReport','ReportController@managerSupplierPaymentReport');
Route::post('/managerSupplierPaymentReportView','ReportController@managerSupplierPaymentReportView');
Route::get('/managerPCPaymentReport','ReportController@managerPCPaymentReport');
Route::post('/managerPCPaymentReportView','ReportController@managerPCPaymentReportView');
Route::get('/managerExpenseReport','ReportController@managerExpenseReport');
Route::post('/managerExpenseReportView','ReportController@managerExpenseReportView');
Route::get('/managerBankStatetmentReport','ReportController@managerBankStatetmentReport');
Route::post('/managerBankStatetmentReportView','ReportController@managerBankStatetmentReportView');
Route::get('/managerCashToBankReport','ReportController@managerCashToBankReport');
Route::post('/managerCashToBankReportView','ReportController@managerCashToBankReportView');
Route::get('/managerBankToCashReport','ReportController@managerBankToCashReport');
Route::post('/managerBankToCashReportView','ReportController@managerBankToCashReportView');
Route::get('/managerCashReceiveReport','ReportController@managerCashReceiveReport');
Route::post('/managerCashReceiveReportView','ReportController@managerCashReceiveReportView');
Route::get('/managerIncomeReport','ReportController@managerIncomeReport');
Route::post('/managerIncomeReportView','ReportController@managerIncomeReportView');
Route::get('/cashierPathologyDueCollectionReport','ReportController@cashierPathologyDueCollectionReport');
Route::post('/cashierPathologyDueCollectionReportView','ReportController@cashierPathologyDueCollectionReportView');
Route::get('/cashierPathologyDoctorDiscountReport','ReportController@cashierPathologyDoctorDiscountReport');
Route::post('/cashierPathologyDoctorDiscountReportView','ReportController@cashierPathologyDoctorDiscountReportView');
Route::get('/cashierOPDDueCollectionReport','ReportController@cashierOPDDueCollectionReport');
Route::post('/cashierOPDDueCollectionReportView','ReportController@cashierOPDDueCollectionReportView');
Route::get('/cashierOPDDoctorDiscountReport','ReportController@cashierOPDDoctorDiscountReport');
Route::post('/cashierOPDDoctorDiscountReportView','ReportController@cashierOPDDoctorDiscountReportView');
#------------------------ END START REPORT -------------------------------#
#------------------------ SETTING ----------------------------------------#
Route::get('/opdSetting','SettingController@opdSetting');
Route::post('/opdSettingInfo','SettingController@opdSettingInfo');
// manger delete setting
Route::get('/managerDeleteSetting','SettingController@managerDeleteSetting');
Route::post('/managerDeleteSettingInfo','SettingController@managerDeleteSettingInfo');
// admin delete setting
Route::get('/adminDeleteSetting','SettingController@adminDeleteSetting');
Route::get('/changeAdminDeleteStatus/{branch_id}','SettingController@changeAdminDeleteStatus');
Route::post('/adminDeleteSettingInfo','SettingController@adminDeleteSettingInfo');
#------------------------ END SETTING-------------------------------------#
#--------------------------------- PRINT ---------------------------------#
Route::get('/printPurchaseBill/{bill}/{cashbook_id}','PrintController@printPurchaseBill');
Route::get('/printPathologyBill/{bill}/{cashbook_id}','PrintController@printPathologyBill');
Route::get('/printA4PathologyBill/{bill}/{cashbook_id}','PrintController@printA4PathologyBill');
Route::get('/printA4OpdBill/{bill}/{opd_bill_id}','PrintController@printA4OpdBill');
Route::get('/printOpdBill/{bill}/{opd_bill_id}','PrintController@printOpdBill');
Route::get('/printIpdAdmissionInvoice/{ipd_admission_id}/{bill_no}','PrintController@printIpdAdmissionInvoice');
// pritn ipd pathology bill
Route::get('/printIpdPathologyBill/{ipd_pathlogy_id}/{bill_no}/{cashbook_id}/{ip_admission_id}','PrintController@printIpdPathologyBill');
Route::get('/printIpdServiceBill/{last_ipd_service_id}/{bill_no}/{cashbook_id}/{ip_admission_id}','PrintController@printIpdServiceBill');
// ipd clearence print
Route::get('/printIpdClearBill/{ipd_clear_bill_id}/{bill_no}/{cashbook_id}/{ip_admission_id}','PrintController@printIpdClearBill');
// ot booking print
Route::get('/printOTBookingInvoice/{ot_booking_id}/{bill_no}/{cashbook_id}','PrintController@printOTBookingInvoice');
Route::get('/printOTClearenceBill/{ot_clear_bill_id}/{bill_no}/{cashbook_id}/{ot_booking_id}','PrintController@printOTClearenceBill');
// cashier report print
Route::post('/printCashierPathologyBillReport','PrintController@printCashierPathologyBillReport');
Route::post('/printCashierOPDBillReport','PrintController@printCashierOPDBillReport');
Route::post('/printCashierIpdAdmissionBillReport','PrintController@printCashierIpdAdmissionBillReport');
Route::post('/printCashierIpdPathologyBillReport','PrintController@printCashierIpdPathologyBillReport');
Route::post('/printCashierIpdServiceBillReport','PrintController@printCashierIpdServiceBillReport');
Route::post('/printCashierIpdClearanceBillReport','PrintController@printCashierIpdClearanceBillReport');
Route::post('/printCashierOTBookingBillReport','PrintController@printCashierOTBookingBillReport');
Route::post('/printCashierOTClearanceBillReport','PrintController@printCashierOTClearanceBillReport');
Route::post('/printCashierCashTransferReport','PrintController@printCashierCashTransferReport');
// manageer report print
Route::post('/printManagerPurchaseReport','PrintController@printManagerPurchaseReport');
Route::post('/printManagerSupplierPaymentReport','PrintController@printManagerSupplierPaymentReport');
Route::post('/printManagerPCPaymentReport','PrintController@printManagerPCPaymentReport');
Route::post('/printManagerExpenseReport','PrintController@printManagerExpenseReport');
Route::post('/printManagerBankStatementReport','PrintController@printManagerBankStatementReport');
Route::post('/printManagerCashToBankReport','PrintController@printManagerCashToBankReport');
Route::post('/printManagerBankToCashReport','PrintController@printManagerBankToCashReport');
Route::post('/printManagerCashReceiveAmtReport','PrintController@printManagerCashReceiveAmtReport');
Route::post('/printManagerIncomeStatement','PrintController@printManagerIncomeStatement');
#--------------------------------- end print-----------------------------------------#
#--------------------------------- Delete -------------------------------------------#
// pathology bill
Route::get('/cashierDeletePathologyBill/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}','DeleteController@cashierDeletePathologyBill');
Route::get('/cashierDeletePathologyDueCollection/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}/{invoice_tr_id}/{status}','DeleteController@cashierDeletePathologyDueCollection');
Route::get('/cashierDeletePathologyDoctorDiscount/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}/{invoice_tr_id}/{status}','DeleteController@cashierDeletePathologyDoctorDiscount');
// opd bill
Route::get('/cashierDeleteOPDBill/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}','DeleteController@cashierDeleteOPDBill');
// delete opd doctor discount
Route::get('/cashierDeleteOPDDoctorDiscount/{id}/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}/{invoice_tr_id}/{status}','DeleteController@cashierDeleteOPDDoctorDiscount');
// delete opd due collection 
Route::get('/cashierDeleteOPDDueCollection/{id}/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}/{invoice_tr_id}/{status}','DeleteController@cashierDeleteOPDDueCollection');
// ipd admission
Route::get('/cashierDeleteIPDAdmission/{id}/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}','DeleteController@cashierDeleteIPDAdmission');
Route::get('/cashierDeleteIPDPathology/{bil_id}/{ipd_admission_id}/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}','DeleteController@cashierDeleteIPDPathology');
Route::get('/cashierDeleteIPDService/{bil_id}/{ipd_admission_id}/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}','DeleteController@cashierDeleteIPDService');
Route::get('/cashierDeleteIPDClearence/{bil_id}/{ipd_admission_id}/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}','DeleteController@cashierDeleteIPDClearence');
// cashier delete otb booking
Route::get('/cashierDeleteOTBooking/{booking_id}/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}','DeleteController@cashierDeleteOTBooking');
Route::get('/cashierDeleteOTStaffPosting/{bill_id}/{booking_id}/{invoice}/{year_invoice}/{daily_invoice}','DeleteController@cashierDeleteOTStaffPosting');
Route::get('/cashierDeleteOTService/{bill_id}/{booking_id}/{invoice}/{year_invoice}/{daily_invoice}','DeleteController@cashierDeleteOTService');
Route::get('/cashierDeleteOTClearence/{bill_id}/{booking_id}/{invoice}/{year_invoice}/{daily_invoice}/{cashbook_id}','DeleteController@cashierDeleteOTClearence');
// cashier delete pending cash trnasfer
Route::get('/cashierDeletePendingCashTransfer/{id}/{status}','DeleteController@cashierDeletePendingCashTransfer');
#---------------------------------- manager delete start----------------------------------------------------------#
// purchase delete
Route::get('/managerPurchaseDelete/{invoice}/{cashbook_id}','DeleteController@managerPurchaseDelete');
#---------------------------------- end manager delete start ------------------------------------------------------#
#--------------------------------- end delete ---------------------------------------------------------------------#

#-----------------------PRESCRIPTION START---------------------------#
// add Medicine Type
Route::get('/addMedicineType','PrescriptionInfoController@addMedicineType');
Route::post('/addMedicineTypeInfo','PrescriptionInfoController@addMedicineTypeInfo');
Route::get('/manageMedicineType','PrescriptionInfoController@manageMedicineType');
Route::get('/deactiveMedicineType/{id}','PrescriptionInfoController@deactiveMedicineType');
// add Medicine
Route::get('/addMedicine','PrescriptionInfoController@addMedicine');
Route::post('/addMedicineInfo','PrescriptionInfoController@addMedicineInfo');
Route::get('/manageMedicine','PrescriptionInfoController@manageMedicine');
Route::get('/deactiveMedicine/{id}','PrescriptionInfoController@deactiveMedicine');
// Unaided Var
Route::get('/addUnaidedVar','PrescriptionInfoController@addUnaidedVar');
Route::post('/addUnaidedVarInfo','PrescriptionInfoController@addUnaidedVarInfo');
Route::get('/manageUnaidedVar','PrescriptionInfoController@manageUnaidedVar');
Route::get('/deactiveVar/{id}','PrescriptionInfoController@deactiveVar');
// Unaided Val
Route::get('/addUnaidedVal','PrescriptionInfoController@addUnaidedVal');
Route::post('/addUnaidedValInfo','PrescriptionInfoController@addUnaidedValInfo');
Route::get('/manageUnaidedVal','PrescriptionInfoController@manageUnaidedVal');
Route::get('/deactiveVal/{id}','PrescriptionInfoController@deactiveVal');
// Pinhole Var
Route::get('/addPinholeVar','PrescriptionInfoController@addPinholeVar');
Route::post('/addPinholeVarInfo','PrescriptionInfoController@addPinholeVarInfo');
Route::get('/managePinholeVar','PrescriptionInfoController@managePinholeVar');
Route::get('/deactivePinholeVar/{id}','PrescriptionInfoController@deactivePinholeVar');
// Pinhole Var
Route::get('/addPinholeVal','PrescriptionInfoController@addPinholeVal');
Route::post('/addPinholeValInfo','PrescriptionInfoController@addPinholeValInfo');
Route::get('/managePinholeVal','PrescriptionInfoController@managePinholeVal');
Route::get('/deactivePinholeVal/{id}','PrescriptionInfoController@deactivePinholeVal');
// Anterior Segment
Route::get('/addAnteriorSegment','PrescriptionInfoController@addAnteriorSegment');
Route::post('/addAnteriorSegmentInfo','PrescriptionInfoController@addAnteriorSegmentInfo');
Route::get('/manageAnteriorSegment','PrescriptionInfoController@manageAnteriorSegment');
Route::get('/deactiveAnteriorSegment/{id}','PrescriptionInfoController@deactiveAnteriorSegment');
// Posteror Segment
Route::get('/addPosterorSegment','PrescriptionInfoController@addPosterorSegment');
Route::post('/addPosterorSegmentInfo','PrescriptionInfoController@addPosterorSegmentInfo');
Route::get('/managePosterorSegment','PrescriptionInfoController@managePosterorSegment');
Route::get('/deactivePosterorSegment/{id}','PrescriptionInfoController@deactivePosterorSegment');
// Diagnosis 
Route::get('/addDisgnosis','PrescriptionInfoController@addDisgnosis');
Route::post('/addDisgnosisInfo','PrescriptionInfoController@addDisgnosisInfo');
Route::get('/manageDisgnosis','PrescriptionInfoController@manageDisgnosis');
Route::get('/deactiveDisgnosis/{id}','PrescriptionInfoController@deactiveDisgnosis');
// Advice
Route::get('/addAdvice','PrescriptionInfoController@addAdvice');
Route::post('/addAdviceInfo','PrescriptionInfoController@addAdviceInfo');
Route::get('/manageAdvice','PrescriptionInfoController@manageAdvice');
Route::get('/deactiveAdvice/{id}','PrescriptionInfoController@deactiveAdvice');
// Followup
Route::get('/addFollowup','PrescriptionInfoController@addFollowup');
Route::post('/addFollowupInfo','PrescriptionInfoController@addFollowupInfo');
Route::get('/manageFollowup','PrescriptionInfoController@manageFollowup');
Route::get('/deactiveFollowup/{id}','PrescriptionInfoController@deactiveFollowup');
// Dose
Route::get('/addDose','PrescriptionInfoController@addDose');
Route::post('/addDoseInfo','PrescriptionInfoController@addDoseInfo');
Route::get('/manageDose','PrescriptionInfoController@manageDose');
Route::get('/deactiveDose/{id}','PrescriptionInfoController@deactiveDose');

#-----------------------PRESCRIPTION END-----------------------------#