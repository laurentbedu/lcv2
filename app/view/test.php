<?php
    // var_dump(Contact_message::selectAll());
    // var_dump(Contact_message::selectById(1));
    // var_dump(Contact_message::selectById(1)->deep());
    var_dump(Contact_message::selectById(1)->update(['message'=>'Nouveau message']));
    // var_dump(Contact_message::insert([[  'fullname' => "JJG",
    //                                     'email' => "jjg@test.com",
    //                                     'message' => "coucou" ],
    //                                     [  'fullname' => "ABC",
    //                                     'email' => "abc@test.com",
    //                                     'message' => "hello" ]]));