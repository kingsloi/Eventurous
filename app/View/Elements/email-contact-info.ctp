<?php

    //set dem variables u get me
    $contactEmail   = $data['contact_email'];
    $contactName  	= $data['contact_name'];
    $contactNumber 	= $data['contact_number'];

?>
<table class="five columns centered-text" style="margin-top:15px">
    <tr>
        <td class="panel">
            <h6 class="details-block-heading">Contact Info</h6>
            <table>
                <tr>
                    <td>
                        <ul class="details-block">
                            <li class="details-block-heading">Email</li>
                            <li class="details-block-data">
                                <a href="mailto:<?php echo $contactEmail; ?>">
									<?php echo $contactName; ?>
								</a>
                            </li>
                            <?php if(!empty($contactNumber)){?>
                                <li class="details-block-heading">Phone:</li>
                                <li class="details-block-data">
                                    <?php echo $contactNumber; ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>