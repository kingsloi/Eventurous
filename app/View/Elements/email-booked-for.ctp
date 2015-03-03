<?php

    //set dem variables u get me
    $employeeName   = $data['fullname'];
    $employeeStore  = $data['store'];
    $employeeEmail  = $data['email'];
    $employeePhone  = $data['phonenumber'];

?>
<table class="five columns centered-text" style="margin-bottom:15px">
    <tr>
        <td class="panel">
            <h6 class="details-block-heading">Employee Details</h6>
            <table>
                <tr>
                    <td>
                        <ul class="details-block">
                            <li class="details-block-heading">Employee</li>
                            <li class="details-block-data">
                                <?php echo $employeeName; ?>
                            </li>
                            <li class="details-block-heading">Store:</li>
                            <li class="details-block-data">
                                <?php echo $employeeStore; ?>
                            </li>
                            <?php if(!empty($employeePhone)){?>
                                <li class="details-block-heading">Phone:</li>
                                <li class="details-block-data">
                                    <?php echo $employeePhone; ?>
                                </li>
                            <?php } ?>
                            <?php if(!empty($employeeEmail)){?>
                                <li class="details-block-heading">Email:</li>
                                <li class="details-block-data">
                                    <?php echo $employeeEmail; ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>