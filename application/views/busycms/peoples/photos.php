<h1 style="margin-bottom:10px;">Photos</h1>
<div id="artistimagelist">
    <ul class="images">
        <?php 
            $sql=$this->db->query("select * from project_images where project_id=".$project->id." order by reorder asc");
            foreach($sql->result() as $image) {  ?>
            <li id="image_<?php echo $image->id ?>">
                <div class="image">
                <img src="<?php echo base_url() ?>uploads/thumb_<?php echo $image->image ?>" />
                </div>
                <div class="menus">
                    <table width="100%" height="100%">
                        <tr>
                            <td style="vertical-align:middle;">
                                <div id="menu<?php echo $image->id ?>">
                    <a href="javascript:void(0)" onclick="edit(<?php echo $image->id ?>)"><span>8</span>Edit Image</a><br/>
                    <a href="javascript:void(0)" onclick="deleteimageapproval(<?php echo $image->id ?>)"><span>X</span>Delete Image</a>

                                </div>
                    <div id="edit<?php echo $image->id ?>" class="imageedit">
                        <form id="imageeditform<?php echo $image->id ?>" onsubmit="return false;">
                            <div id="imageeditform<?php echo $image->id ?>_back">
                            </div>
                            <input type="hidden" name="id" value="<?php echo $image->id ?>" />
                            <textarea name="description" style="height:100px;"><?php echo $image->description ?></textarea>
                            <input type="checkbox" name="view" <?php if ($image->view==1) { echo "checked"; } ?> style="margin-top:5px;" />
                            <div align="right">
                                <button onclick="submitform('busycms/projectimageeditform/','imageeditform<?php echo $image->id ?>')">Kaydet</button>
                            </div>
                        </form>
                    </div>                                                
                            </td>
                        </tr>
                    </table>
                    </div>
            </li>
            <?php } ?>
        <li id="addartistimage">
            <img src="<?php echo base_url() ?>busycms/images/add-photo.png" onclick="$('#uploadprojectimageinput').click();" />
        </li>
    </ul>
    <div style="width:1px;height:1px;overflow:hidden;">
    <form method="post" id="uploadprojectimage" name="uploadprojectimage" action="<?php echo base_url() ?>busycms/uploadimageproject/<?php echo $project->id ?>" enctype="multipart/form-data" target="ajax3">
        <input id="uploadprojectimageinput" name="images[]" type="file" min="1" max="30" multiple="multiple"  onchange="$('#uploadprojectimage').submit();" /><br/><br/>
        <input type="submit" value="Upload" />
    </form>
        <div style="height:0px;width:0px;overflow:hidden;">
        <iframe id="ajax3" name="ajax3"></iframe>
        </div>
    </div>
</div>