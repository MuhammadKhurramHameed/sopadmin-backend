<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\BlogAndAlbumController;
use App\Http\Controllers\employeeAttandance;
use App\Http\Controllers\programController;
use App\Http\Controllers\studentController;
use App\Http\Controllers\batchController;
use App\Http\Controllers\ticketController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\userController;
use App\Http\Controllers\settingsController;
use App\Http\Controllers\lmsController;
use App\Http\Controllers\examController;
use App\Http\Controllers\notificationController;
use App\Http\Controllers\faqController;
use App\Http\Controllers\phaseCenterController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\blockController;
use App\Http\Controllers\seatController;
use App\Http\Controllers\attendanceController;
use App\Http\Controllers\programphaseController;
use App\Http\Controllers\resultController;
use App\Http\Controllers\accountsController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\InventoryManagementController;
use App\Http\Controllers\employeetimingController;
use App\Http\Controllers\employeeleaveController;


Route::middleware('cors')->group(function(){
Route::any('login', [loginController::class, 'login']);
Route::middleware('login.check')->group(function(){	
    Route::any('rolelist', [settingsController::class, 'rolelist']);
    Route::any('gradelist', [settingsController::class, 'gradelist']);
    Route::any('programbatchlist', [settingsController::class, 'programbatchlist']);
    Route::any('subjectlist', [settingsController::class, 'subjectlist']);
    Route::any('programphaselist', [settingsController::class, 'programphaselist']);
    Route::any('deleteprogramphase', [settingsController::class, 'deleteprogramphase']);
    Route::any('saveprovince', [settingsController::class, 'saveprovince']);
    Route::any('savedistrict', [settingsController::class, 'savedistrict']);

    Route::any('saveprogram', [programController::class, 'saveprogram']);
    Route::any('updateprogram', [programController::class, 'updateprogram']);
    Route::any('programlist', [programController::class, 'programlist']);
    Route::any('deleteprogram', [programController::class, 'deleteprogram']);

    Route::any('studentlist', [studentController::class, 'studentlist']);
    Route::any('paidstudentlist', [studentController::class, 'paidstudentlist']);
    Route::any('studentdetails', [studentController::class, 'studentdetails']);
    Route::any('deletestudent', [studentController::class, 'deletestudent']);

    Route::any('savebatch', [batchController::class, 'savebatch']);
    Route::any('updatebatch', [batchController::class, 'updatebatch']);
    Route::any('batchlist', [batchController::class, 'batchlist']);
    Route::any('deletebatch', [batchController::class, 'deletebatch']);

    Route::any('ticketlist', [ticketController::class, 'ticketlist']);
    Route::any('proceedticket', [ticketController::class, 'proceedticket']);

    Route::any('userlist', [userController::class, 'userlist']); 
    Route::any('userlistdropdown', [userController::class, 'userlistdropdown']); 
    Route::any('controllerlist', [userController::class, 'controllerlist']); 
    Route::any('invigilatorist', [userController::class, 'invigilatorist']); 
    Route::any('saveuser', [userController::class, 'saveuser']); 
    Route::any('updateuser', [userController::class, 'updateuser']); 
    Route::any('deleteuser', [userController::class, 'deleteuser']); 
    Route::any('userdetail', [userController::class, 'userdetail']); 

    Route::any('uploadlms', [lmsController::class, 'uploadlms']); 
    Route::any('lmslist', [lmsController::class, 'lmslist']); 

    Route::any('savenotification', [notificationController::class, 'savenotification']); 
    Route::any('notificationlist', [notificationController::class, 'notificationlist']); 

    Route::any('saveexam', [examController::class, 'saveexam']); 
    Route::any('examlist', [examController::class, 'examlist']); 
    Route::any('updateexam', [examController::class, 'updateexam']); 
    Route::any('deleteexam', [examController::class, 'deleteexam']); 
    Route::any('savequestion', [examController::class, 'savequestion']); 
    Route::any('questionlist', [examController::class, 'questionlist']); 
    Route::any('filterquestionlist', [examController::class, 'filterquestionlist']); 
    Route::any('deletequestion', [examController::class, 'deletequestion']); 
    Route::any('questionapprovallist', [examController::class, 'questionapprovallist']); 
    Route::any('approvequestion', [examController::class, 'approvequestion']); 

    Route::any('savefaq', [faqController::class, 'savefaq']); 
    Route::any('faqlist', [faqController::class, 'faqlist']); 
    
    Route::any('get-province', [ProvinceController::class, 'getProvince']);
    Route::any('get-district', [ProvinceController::class, 'getDistrict']);
    Route::any('getalldistrict', [ProvinceController::class, 'getalldistrict']);
    
    Route::any('savePhaseCenter', [phaseCenterController::class, 'savePhaseCenter']);
    Route::any('updatePhaseCenter', [phaseCenterController::class, 'updatePhaseCenter']);
    Route::any('getPhaseCenter', [phaseCenterController::class, 'getPhaseCenterList']);
    Route::any('deletePhaseCenter', [phaseCenterController::class, 'deletePhaseCenter']);
    Route::any('phaseCenterByDistrict', [phaseCenterController::class, 'phaseCenterByDistrict']);

    Route::any('getStartRollNo/{programId}/{PhaseCenterid}', [blockController::class, 'getStartRollNo']);
    Route::any('createBlock', [blockController::class, 'createBlock']);
    Route::any('getBlockList', [blockController::class, 'getBlockList']);
    Route::any('updateBlock', [blockController::class, 'updateBlock']);
    Route::any('deleteBlock', [blockController::class, 'deleteBlock']);
    Route::any('blockListByPhaseCenter', [blockController::class, 'blockListByPhaseCenter']);

    Route::any('blockwiseseting', [seatController::class, 'blockwiseseting']);

    Route::any('blockwiseattendance', [attendanceController::class, 'blockwiseattendance']);
    Route::any('markattendance', [attendanceController::class, 'markattendance']);

    Route::any('saveprogramphase', [programphaseController::class, 'saveprogramphase']);
    Route::any('updateprogramphase', [programphaseController::class, 'updateprogramphase']);
    Route::any('programphaselist', [programphaseController::class, 'programphaselist']);
    Route::any('deleteprogramphase', [programphaseController::class, 'deleteprogramphase']);

    Route::any('ResultEntry', [resultController::class, 'ResultEntry']);
    Route::any('markResult', [resultController::class, 'markResult']);
    Route::any('meritList', [resultController::class, 'meritList']);
    Route::any('ResultList', [resultController::class, 'ResultList']);
    Route::get('onlineResultgenerator', [resultController::class, 'onlineResultgenerator']);
    Route::get('getResult', [resultController::class, 'getResult']);
    Route::get('getPrograms', [resultController::class, 'getPrograms']);
    Route::get('getExamByProgramId', [resultController::class, 'getExamByProgramId']);
    Route::get('getExamLevel', [resultController::class, 'getExamLevel']);

    Route::any('paymentlist', [accountsController::class, 'paymentlist']);
    Route::any('updatepaymentstatus', [accountsController::class, 'updatepaymentstatus']);

    Route::get('admindashboard', [dashboardController::class, 'admindashboard']);

    Route::any('InventoryAdd', [InventoryManagementController::class, 'InventoryAdd']);
    Route::any('InventoryList', [InventoryManagementController::class, 'InventoryList']);
    Route::get('removeInventory', [InventoryManagementController::class, 'removeInventory']);
    Route::get('consumableItemList', [InventoryManagementController::class, 'consumableItemList']);
    Route::get('AddQuantity', [InventoryManagementController::class, 'AddQuantity']);
    Route::any('updateQuantity', [InventoryManagementController::class, 'updateQuantity']);
    Route::any('ConsumedItemList', [InventoryManagementController::class, 'ConsumedItemList']);
    Route::any('addOffices', [InventoryManagementController::class, 'addOffices']);
    Route::any('officesList', [InventoryManagementController::class, 'officesList']);
    Route::any('addExpenses', [InventoryManagementController::class, 'addExpenses']);
    Route::any('expensesList', [InventoryManagementController::class, 'expensesList']);
    Route::any('addCars', [InventoryManagementController::class, 'addCars']);
    Route::any('carList', [InventoryManagementController::class, 'carList']);

    Route::any('markAttandance', [employeeAttandance::class, 'markAttandance']);
    Route::any('isMarked', [employeeAttandance::class, 'isMarked']);
    Route::any('attandanceListForUser', [employeeAttandance::class, 'attandanceListForUser']);
    Route::any('markLeave', [employeeAttandance::class, 'markLeave']);
    Route::any('employeelist', [employeeAttandance::class, 'employeelist']);

    Route::any('Totaldebits', [accountsController::class, 'Totaldebits']);
    Route::any('debitList', [accountsController::class, 'debitList']);
    Route::any('Totalcredits', [accountsController::class, 'Totalcredits']);
    Route::any('creditList', [accountsController::class, 'creditList']);
    Route::any('balancesheet', [accountsController::class, 'balancesheet']);

    Route::any('SaveBlog', [BlogAndAlbumController::class, 'SaveBlog']);
    Route::any('ActiveOrDeactiveBlog', [BlogAndAlbumController::class, 'ActiveOrDeactiveBlog']);
    Route::any('blogList', [BlogAndAlbumController::class, 'blogList']);
    Route::any('updateBlog', [BlogAndAlbumController::class, 'updateBlog']);    

    Route::any('saveemptiming', [employeetimingController::class, 'saveemptiming']);    
    Route::any('emptiminglist', [employeetimingController::class, 'emptiminglist']);    
    Route::any('deleteemptiming', [employeetimingController::class, 'deleteemptiming']);    

    Route::any('saveempleave', [employeeleaveController::class, 'saveempleave']);    
    Route::any('empleavelist', [employeeleaveController::class, 'empleavelist']);    
    Route::any('proceedempleave', [employeeleaveController::class, 'proceedempleave']); 
    
    Route::any('createAlbum', [GalleryController::class, 'createAlbum']); 
    Route::any('listOfAlbums', [GalleryController::class, 'listOfAlbums']);   
    Route::any('listOfGalleryImages', [GalleryController::class, 'listOfGalleryImages']);   
    Route::any('addMoreImages', [GalleryController::class, 'addMoreImages']);   
    Route::any('deleteImages', [GalleryController::class, 'deleteImages']);   
});
});