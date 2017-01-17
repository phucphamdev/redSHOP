<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('redshopjquery.ui');
JHtml::script('com_redshop/jquery.iframe-transport.js', false, true);
JHtml::script('com_redshop/jquery.fileupload.js', false, true);

// @TODO: Move to config
$allowFileTypes = array('text/csv');

// @TODO: Move to config. In bytes.
$allowMaxFileSize = 1000000;

// @TODO: Move to config. In bytes.
$allowMinFileSize = 1;

// Defines encoding used in import
$characterSets = array(
	'ISO-8859-1'  => 'COM_REDSHOP_IMPORT_CHARS_ISO88591',
	'ISO-8859-5'  => 'COM_REDSHOP_IMPORT_CHARS_ISO88595',
	'ISO-8859-15' => 'COM_REDSHOP_IMPORT_CHARS_ISO885915',
	'UTF-8'       => 'COM_REDSHOP_IMPORT_CHARS_UTF8',
	'cp866'       => 'COM_REDSHOP_IMPORT_CHARS_CP866',
	'cp1251'      => 'COM_REDSHOP_IMPORT_CHARS_CP1251',
	'cp1252'      => 'COM_REDSHOP_IMPORT_CHARS_CP1252',
	'KOI8-R'      => 'COM_REDSHOP_IMPORT_CHARS_KOI8R',
	'BIG5'        => 'COM_REDSHOP_IMPORT_CHARS_BIG5',
	'GB2312'      => 'COM_REDSHOP_IMPORT_CHARS_GB2312',
	'BIG5-HKSCS'  => 'COM_REDSHOP_IMPORT_CHARS_BIG5HKSCS',
	'Shift_JIS'   => 'COM_REDSHOP_IMPORT_CHARS_SHIFTJIS',
	'EUC-JP'      => 'COM_REDSHOP_IMPORT_CHARS_EUCJP',
	'MacRoman'    => 'COM_REDSHOP_IMPORT_CHARS_MACROMAN'
);

// Creating JOption for JSelect box.
foreach ($characterSets as $char => $name)
{
	$title       = sprintf(JText::_($name), $char);
	$encodings[] = JHTML::_('select.option', $char, $title);
}
?>

<?php if (empty($this->imports)): ?>
    <div class="alert alert-warning">
        <span class="close" data-dismiss="alert">×</span>
        <h4 class="alert-heading">
            <i class="fa fa-exclamation-triangle"></i> <?php echo JText::_('WARNING') ?>
        </h4>
        <div>
            <p><?php echo JText::_('COM_REDSHOP_IMPORT_WARNING_MISSING_PLUGIN') ?></p>
        </div>
    </div>
<?php else: ?>
    <script type="text/javascript">
        var plugin = '';
        var total = 0;
        var folder = '';
        var itemRun = 1;
        var allowFileType = ["<?php echo implode('","', $allowFileTypes) ?>"];
        var allowMaxFileSize = <?php echo $allowMaxFileSize ?>;
        var allowMinFileSize = <?php echo $allowMinFileSize ?>;

        (function ($) {
            $(document).ready(function () {
                $("#import_plugins input[type='radio']").change(function (e) {
                    plugin = $(this).val();
                    $("#import_config").addClass('disabled muted');
                    $("#import_process_msg").removeClass("alert-success").removeClass("alert-danger");
                    $("#import_process_msg_body").html("");
                    $("#import_process_bar").html('0%').css("width", "0%");
                    $("#import_upload_progress").html('0%').css("width", "0%");

                    // Load specific configuration of plugin
                    $.post(
                        "index.php?option=com_ajax&plugin=" + plugin + "_config&group=redshop_import&format=raw",
                        $("#adminForm").serialize(),
                        function (response) {
                            $("#import_config_body").empty().html(response);
                            $("select").select2({});
                            $("#import_config").removeClass('disabled muted');
                            $("#import_btn_start").prop("disabled", false).removeClass("disabled");
                        }
                    );
                });

                $("#fileupload").fileupload({
                    dataType: "json",
                    singleFileUploads: true,
                    done: function (e, data) {
                        $("#import_process_msg").removeClass("alert-danger").addClass("alert-success");
                        $("#import_process_msg_body").empty();

                        if (data.result.status == 1) {
                            $("#import_process_msg").addClass("alert-success");
                            $("#import_process_msg_body").append("<p>" + data.result.msg + "</p>");
                            total = data.result.lines - 1;
                            folder = data.result.folder;
                            $("#import_count").html(total);

                            run_import(0);
                        } else {
                            $("#import_process_msg").addClass("alert-danger");
                            $("#import_process_msg_body").append("<p>" + data.result.msg + "</p>");
                            $("#import_count").empty();
                        }
                    },
                    add: function (e, data) {
                        if (allowFileType.indexOf(data.files[0].type) == -1) {
                            $("#import_process_msg").removeClass("alert-success").addClass("alert-danger");
                            $("#import_process_msg_body").text("<?php echo JText::_('COM_REDSHOP_IMPORT_ERROR_FILE_TYPE') ?>");

                            return false;
                        }

                        if (data.files[0].size > allowMaxFileSize) {
                            $("#import_process_msg").removeClass("alert-success").addClass("alert-danger");
                            $("#import_process_msg_body").text("<?php echo JText::sprintf('COM_REDSHOP_IMPORT_ERROR_FILE_MAX_SIZE', $allowMaxFileSize) ?>");

                            return false;
                        }

                        if (data.files[0].size < allowMinFileSize) {
                            $("#import_process_msg").removeClass("alert-success").addClass("alert-danger");
                            $("#import_process_msg_body").text("<?php echo JText::sprintf('COM_REDSHOP_IMPORT_ERROR_FILE_MIN_SIZE', $allowMinFileSize) ?>");

                            return false;
                        }

                        data.submit();
                    },
                    progressall: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);

                        $("#import_upload_progress").html(progress + "%").css("width", progress + "%");
                    },
                    error: function (e, text, error) {
                        $("#import_plugins").removeClass("disabled muted");
                        $("#import_config").removeClass("disabled muted");

                        $("#import_process_msg").removeClass("alert-success").addClass("alert-danger");
                        $("#import_process_msg_body").html(error);
                    }
                });

                $("#import_btn_start")
                    .addClass("disabled")
                    .prop("disabled", true)
                    .click(function (event) {
                        $("#import_plugins").addClass("disabled muted");
                        $("#import_config").addClass("disabled muted");

                        $("#import_process_msg").removeClass("alert-success").removeClass("alert-danger");
                        $("#import_process_msg_body").html("");

                        $("#import_process_bar").html('0%').css("width", "0%");
                        $("#fileupload").click();

                        event.preventDefault();
                    });
            });
        })(jQuery);
    </script>

    <script type="text/javascript">
        function run_import(startIndex) {
            (function ($) {
                var url = "index.php?option=com_ajax&plugin=" + plugin + "_import&group=redshop_import&format=raw";
                var data = $("#adminForm").serialize();
                data += "&folder=" + folder;

                $.post(
                    url,
                    data,
                    function (response) {
                        var success = startIndex + itemRun;
                        var percent = 0.0;
                        var $bar = $("#import_process_bar");

                        if (success > total) {
                            percent = 100;
                        } else {
                            percent = (success / total) * 100;
                        }

                        if (percent > 100) {
                            percent = 100;
                        }

                        $bar.css("width", percent + "%");
                        $bar.html(percent.toFixed(2) + "%");

                        if (response == 1) {
                            run_import(success);
                        }
                        else if (response == 0 || success > total) {
                            total = 0;
                            // $("#import_process_msg").addClass("alert-success").removeClass("alert-danger");
                            // $("#import_process_msg_body").append("<?php echo JText::_('COM_REDSHOP_IMPORT_DONE') ?>");
                            $("#import_plugins").removeClass("disabled muted");
                            $("#import_config").removeClass("disabled muted");
                        }
                        else {
                            total = 0;
                            $("#import_plugins").removeClass("disabled muted");
                            $("#import_config").removeClass("disabled muted");
                            // $("#import_process_msg").removeClass("alert-success").addClass("alert-danger");
                            $("#import_process_msg_body").html(response);
                        }
                    }
                );
            })(jQuery);
        }
    </script>

    <form action="index.php?option=com_redshop&view=import" method="post" name="adminForm" id="adminForm">
        <div class="row">
            <div class="col-md-6">
                <!-- Step 1. Choose plugin -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
							<?php echo JText::_('COM_REDSHOP_IMPORT_STEP_1') ?>
                        </h4>
                    </div>
                    <div class="panel-body" id="import_plugins">
						<?php foreach ($this->imports as $import): ?>
                            <label>
                                <input type="radio" value="<?php echo $import->name ?>"
                                       name="plugin_name"/> <?php echo JText::_('PLG_REDSHOP_IMPORT_' . strtoupper($import->name) . '_TITLE') ?>
                            </label>
						<?php endforeach; ?>
                    </div>
                </div>
                <!-- Step 1. End -->
            </div>
            <div class="col-md-6">
                <!-- Step 2. Config -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
							<?php echo JText::_('COM_REDSHOP_IMPORT_STEP_2') ?>
                        </h4>
                    </div>
                    <div class="panel-body">
                        <div id="import_config">
                            <fieldset class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">
										<?php echo JText::_('COM_REDSHOP_IMPORT_CONFIG_SEPARATOR') ?>
                                    </label>
                                    <div class="col-md-10">
                                        <input type="text" value="," class="form-control" maxlength="1" name="separator"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label"><?php echo JText::_('COM_REDSHOP_IMPORT_ENCODING') ?></label>
                                    <div class="col-md-10">
										<?php
										echo JHTML::_(
											'select.genericlist',
											$encodings,
											'encoding',
											'class="form-control"',
											'value',
											'text',
											'UTF-8'
										);
										?>
                                    </div>
                                </div>
                                <div id="import_config_body"></div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <!-- Step 2. End -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <!-- Step 3. Process -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title" id="import_process_title"><?php echo JText::_('COM_REDSHOP_IMPORT_STEP_3') ?></h4>
                    </div>
                    <div id="import_process_panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <button class="btn btn-primary btn-large" id="import_btn_start" type="button">
										<?php echo JText::_('COM_REDSHOP_IMPORT_SELECT_FILE') ?>&nbsp;&nbsp;<i class="fa fa-upload"></i>
                                    </button>
                                    <input id="fileupload" type="file" name="csv_file" class="hidden"
                                           data-url="index.php?option=com_redshop&task=import.uploadFile"/>
                                    <p></p>
                                    <div class="progress">
                                        <div id="import_upload_progress" class="progress-bar" role="progressbar"
                                             aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%
                                        </div>
                                    </div>
                                    <hr/>
                                    <h3><?php echo JText::_('COM_REDSHOP_IMPORT_DATA_IMPORT') ?>: <span id="import_count"></span></h3>
                                    <div class="progress">
                                        <div id="import_process_bar" class="progress-bar progress-bar-success" role="progressbar"
                                             aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                            0%
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div id="import_process_msg" class="alert">
                                            <h4><?php echo JText::_('COM_REDSHOP_IMPORT_LOG') ?></h4>
                                            <div id="import_process_msg_body"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Step 3. End -->
            </div>
        </div>

        <!-- Hidden field -->
		<?php echo JHtml::_('form.token') ?>
    </form>
<?php endif; ?>