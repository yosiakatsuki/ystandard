"use strict";(function($){wp.customize.bind("ready",function(){var customize=this;var selector="#_customize-input-background_repeat,#_customize-input-background_attachment,#_customize-input-background_size,[name=\"background-position\"]";$(selector).change(function(){setBackgroundPresetCustom($)})})})(jQuery);function setBackgroundPresetCustom($){var preset=$("#_customize-input-background_preset");if("default"===preset.val()){preset.val("custom")}}