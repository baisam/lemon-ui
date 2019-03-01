// adminlte Gruntfile
module.exports = function (grunt) {

  'use strict';

  grunt.initConfig({
    // Copy All files
    copy: {
      AdminLTE: {
        expand: true,
        cwd: 'node_modules/admin-lte/dist/',
        src: '**/*', // Source files
        dest: 'assets/admin-lte/',
      },
      adminlteless: {
        expand: true,
        cwd: 'node_modules/admin-lte/build/less/',
        src: '**/*', // Source files
        dest: 'assets/admin-lte/less/',
      },
      bootstrapless: {
        expand: true,
        cwd: 'node_modules/admin-lte/build/bootstrap-less/',
        src: '**/*', // Source files
        dest: 'assets/admin-lte/bootstrap-less/',
      },
      bootstrap: {
        expand: true,
        cwd: 'node_modules/bootstrap/',
        src: '**/*', // Source files
        dest: 'assets/bootstrap/',
      },
      colorpicker: {
        expand: true,
        cwd: 'node_modules/bootstrap-colorpicker/',
        src: '**/*', // Source files
        dest: 'assets/bootstrap-colorpicker/',
      },
      datepicker: {
        expand: true,
        cwd: 'node_modules/bootstrap-datepicker/',
        src: '**/*', // Source files
        dest: 'assets/bootstrap-datepicker/',
      },
      daterangepicker: {
        expand: true,
        cwd: 'node_modules/bootstrap-daterangepicker/',
        src: '**/*', // Source files
        dest: 'assets/bootstrap-daterangepicker/',
      },
      editable: {
        expand: true,
        cwd: 'node_modules/bootstrap-editable/',
        src: '**/*', // Source files
        dest: 'assets/bootstrap-editable/',
      },
      fileinput: {
        expand: true,
        cwd: 'node_modules/bootstrap-fileinput/',
        src: '**/*', // Source files
        dest: 'assets/bootstrap-fileinput/',
      },
      numberinput: {
        expand: true,
        cwd: 'node_modules/bootstrap-input-spinner/src/',
        src: '**/*', // Source files
        dest: 'assets/bootstrap-numberinput/',
      },
      maxlength: {
        expand: true,
        cwd: 'node_modules/bootstrap-maxlength/',
        src: '**/*', // Source files
        dest: 'assets/bootstrap-maxlength/',
      },
      slider: {
        expand: true,
        cwd: 'node_modules/bootstrap-slider/',
        src: '**/*', // Source files
        dest: 'assets/bootstrap-slider/',
      },
      switch: {
        expand: true,
        cwd: 'node_modules/bootstrap-switch/',
        src: '**/*', // Source files
        dest: 'assets/bootstrap-switch/',
      },
      timepicker: {
        expand: true,
        cwd: 'node_modules/bootstrap-timepicker/',
        src: '**/*', // Source files
        dest: 'assets/bootstrap-timepicker/',
      },
      chart: {
        expand: true,
        cwd: 'node_modules/chart.js/',
        src: '**/*', // Source files
        dest: 'assets/chart.js/',
      },
      ckeditor: {
        expand: true,
        cwd: 'node_modules/ckeditor/',
        src: '**/*', // Source files
        dest: 'assets/ckeditor/',
      },
      datatables: {
        expand: true,
        cwd: 'node_modules/datatables.net/js/',
        src: '**/*', // Source files
        dest: 'assets/datatables/',
      },
      "datatables-bs": {
          expand: true,
          cwd: 'node_modules/datatables.net-bs/',
          src: '**/*', // Source files
          dest: 'assets/datatables-bs/',
      },
      "datatables-buttons": {
        expand: true,
        cwd: 'node_modules/datatables.net-buttons/',
        src: '**/*', // Source files
        dest: 'assets/datatables/plugins/buttons/',
      },
      "datatables-buttons-bs": {
        expand: true,
        cwd: 'node_modules/datatables.net-buttons-bs/',
        src: '**/*', // Source files
        dest: 'assets/datatables-bs/plugins/buttons/',
      },
      "datatables-colreorder": {
        expand: true,
        cwd: 'node_modules/datatables.net-colreorder/js/',
        src: '**/*', // Source files
        dest: 'assets/datatables/plugins/colreorder/',
      },
      "datatables-colreorder-bs": {
        expand: true,
        cwd: 'node_modules/datatables.net-colreorder-bs/',
        src: '**/*', // Source files
        dest: 'assets/datatables-bs/plugins/colreorder/',
      },
      "datatables.net-fixedcolumns": {
          expand: true,
          cwd: 'node_modules/datatables.net-fixedcolumns/js/',
          src: '**/*', // Source files
          dest: 'assets/datatables/plugins/fixedcolumns/',
      },
      "datatables.net-fixedcolumns-bs": {
          expand: true,
          cwd: 'node_modules/datatables.net-fixedcolumns-bs/',
          src: '**/*', // Source files
          dest: 'assets/datatables-bs/plugins/fixedcolumns/',
      },
      "datatables.net-fixedheader": {
        expand: true,
        cwd: 'node_modules/datatables.net-fixedheader/js/',
        src: '**/*', // Source files
        dest: 'assets/datatables/plugins/fixedheader/',
      },
      "datatables.net-fixedheader-bs": {
        expand: true,
        cwd: 'node_modules/datatables.net-fixedheader-bs/',
        src: '**/*', // Source files
        dest: 'assets/datatables-bs/plugins/fixedheader/',
      },
      "datatables.net-keytable": {
        expand: true,
        cwd: 'node_modules/datatables.net-keytable/js/',
        src: '**/*', // Source files
        dest: 'assets/datatables/plugins/keytable/',
      },
      "datatables.net-keytable-bs": {
        expand: true,
        cwd: 'node_modules/datatables.net-keytable-bs/',
        src: '**/*', // Source files
        dest: 'assets/datatables-bs/plugins/keytable/',
      },
      "datatables.net-responsive": {
        expand: true,
        cwd: 'node_modules/datatables.net-responsive/js/',
        src: '**/*', // Source files
        dest: 'assets/datatables/plugins/responsive/',
      },
      "datatables.net-responsive-bs": {
        expand: true,
        cwd: 'node_modules/datatables.net-responsive-bs/',
        src: '**/*', // Source files
        dest: 'assets/datatables-bs/plugins/responsive/',
      },
      "datatables.net-rowgroup": {
        expand: true,
        cwd: 'node_modules/datatables.net-rowgroup/js/',
        src: '**/*', // Source files
        dest: 'assets/datatables/plugins/rowgroup/',
      },
      "datatables.net-rowgroup-bs": {
        expand: true,
        cwd: 'node_modules/datatables.net-rowgroup-bs/',
        src: '**/*', // Source files
        dest: 'assets/datatables-bs/plugins/rowgroup/',
      },
      "datatables.net-rowreorder": {
        expand: true,
        cwd: 'node_modules/datatables.net-rowreorder/js/',
        src: '**/*', // Source files
        dest: 'assets/datatables/plugins/rowreorder/',
      },
      "datatables.net-rowreorder-bs": {
        expand: true,
        cwd: 'node_modules/datatables.net-rowreorder-bs/',
        src: '**/*', // Source files
        dest: 'assets/datatables-bs/plugins/rowreorder/',
      },
      "datatables.net-scroller": {
        expand: true,
        cwd: 'node_modules/datatables.net-scroller/js/',
        src: '**/*', // Source files
        dest: 'assets/datatables/plugins/scroller/',
      },
      "datatables.net-scroller-bs": {
        expand: true,
        cwd: 'node_modules/datatables.net-scroller-bs/',
        src: '**/*', // Source files
        dest: 'assets/datatables-bs/plugins/scroller/',
      },
      "datatables.net-select": {
        expand: true,
        cwd: 'node_modules/datatables.net-select/js/',
        src: '**/*', // Source files
        dest: 'assets/datatables/plugins/select/',
      },
      "datatables.net-select-bs": {
        expand: true,
        cwd: 'node_modules/datatables.net-select-bs/',
        src: '**/*', // Source files
        dest: 'assets/datatables-bs/plugins/select/',
      },
      datetimepicker: {
        expand: true,
        cwd: 'node_modules/eonasdan-bootstrap-datetimepicker/',
        src: '**/*', // Source files
        dest: 'assets/bootstrap-datetimepicker/',
      },
      fastclick: {
        expand: true,
        cwd: 'node_modules/fastclick/lib/',
        src: '**/*', // Source files
        dest: 'assets/fastclick/',
      },
      flot: {
        expand: true,
        cwd: 'node_modules/flot/',
        src: '**/*', // Source files
        dest: 'assets/flot/',
      },
      fontawesome: {
        expand: true,
        cwd: 'node_modules/font-awesome/',
        src: '**/*', // Source files
        dest: 'assets/font-awesome/',
      },
      fullcalendar: {
        expand: true,
        cwd: 'node_modules/fullcalendar/dist/',
        src: '**/*', // Source files
        dest: 'assets/fullcalendar/',
      },
      html5shiv: {
        expand: true,
        cwd: 'node_modules/html5shiv/',
        src: '**/*', // Source files
        dest: 'assets/html5shiv/',
      },
      icheck: {
          expand: true,
          cwd: 'node_modules/icheck/',
          src: '**/*', // Source files
          dest: 'assets/icheck/',
      },
      inputmask: {
        expand: true,
        cwd: 'node_modules/inputmask/',
        src: '**/*', // Source files
        dest: 'assets/inputmask/',
      },
      ionrangeslider: {
        expand: true,
        cwd: 'node_modules/ion-rangeslider/',
        src: '**/*', // Source files
        dest: 'assets/ion-rangeslider/',
      },
      ionicons: {
        expand: true,
        cwd: 'node_modules/ionicons/dist/',
        src: '**/*', // Source files
        dest: 'assets/ionicons/',
      },
      jquery: {
        expand: true,
        cwd: 'node_modules/jquery/dist/',
        src: '**/*', // Source files
        dest: 'assets/jquery/',
      },
      jeditable: {
        expand: true,
        cwd: 'node_modules/jquery-jeditable/src/',
        src: '**/*', // Source files
        dest: 'assets/jquery-jeditable/',
      },
      knob: {
        expand: true,
        cwd: 'node_modules/jquery-knob/',
        src: '**/*', // Source files
        dest: 'assets/jquery-knob/',
      },
      pjax: {
        expand: true,
        cwd: 'node_modules/jquery-pjax/',
        src: '**/*', // Source files
        dest: 'assets/jquery-pjax/',
      },
      mousewheel: {
          expand: true,
          cwd: 'node_modules/jquery-mousewheel/',
          src: '**/*', // Source files
          dest: 'assets/jquery-mousewheel/',
      },
      jqslimscroll: {
        expand: true,
        cwd: 'node_modules/jquery-slimscroll/',
        src: '**/*', // Source files
        dest: 'assets/jquery-slimscroll/',
      },
      sparkline: {
        expand: true,
        cwd: 'node_modules/jquery-sparkline/',
        src: '**/*', // Source files
        dest: 'assets/jquery-sparkline/',
      },
      ui: {
        expand: true,
        cwd: 'node_modules/admin-lte/bower_components/jquery-ui/',
        src: '**/*', // Source files
        dest: 'assets/jquery-ui/',
      },
      jvectormap: {
        expand: true,
        cwd: 'node_modules/jvectormap/',
        src: '**/*', // Source files
        dest: 'assets/jvectormap/',
      },
      moment: {
        expand: true,
        cwd: 'node_modules/moment/',
        src: '**/*', // Source files
        dest: 'assets/moment/',
      },
      timezone: {
          expand: true,
          cwd: 'node_modules/moment-timezone/builds/',
          src: '**/*', // Source files
          dest: 'assets/moment-timezone/',
      },
      morris: {
        expand: true,
        cwd: 'node_modules/morris.js/',
        src: '**/*', // Source files
        dest: 'assets/morris.js/',
      },
      nestable: {
        expand: true,
        cwd: 'node_modules/nestable/',
        src: '**/*', // Source files
        dest: 'assets/nestable/',
      },
      pace: {
        expand: true,
        cwd: 'node_modules/pace-progressbar/',
        src: '**/*', // Source files
        dest: 'assets/pace/',
      },
      raphael: {
        expand: true,
        cwd: 'node_modules/raphael/',
        src: '**/*', // Source files
        dest: 'assets/raphael/',
      },
      select2: {
        expand: true,
        cwd: 'node_modules/select2/',
        src: '**/*', // Source files
        dest: 'assets/select2/',
      },
      slimscroll: {
          expand: true,
          cwd: 'node_modules/slimscroll/',
          src: '**/*', // Source files
          dest: 'assets/slimscroll/',
      },
      smartwizard: {
          expand: true,
          cwd: 'node_modules/smartwizard/',
          src: '**/*', // Source files
          dest: 'assets/smartwizard/',
      },
      toastr: {
          expand: true,
          cwd: 'node_modules/toastr/',
          src: '**/*', // Source files
          dest: 'assets/toastr/',
      }
    },

    // Delete images in build directory
    // After compressing the images in the AdminLTE dir, there is no need
    // for them
    clean: {
      AdminLTE: [
        "assets/jquery/",
        "assets/bootstrap/",
        "assets/admin-lte/"
      ],
      Plugins:[
        "assets/bootstrap-colorpicker/",
        "assets/bootstrap-datepicker/",
        "assets/bootstrap-daterangepicker/",
        "assets/bootstrap-editable/",
        "assets/bootstrap-fileinput/",
        "assets/bootstrap-numberinput/",
        "assets/bootstrap-maxlength/",
        "assets/bootstrap-slider/",
        "assets/bootstrap-switch/",
        "assets/bootstrap-timepicker/",
        "assets/bootstrap-datetimepicker/",
        "assets/chart.js/",
        "assets/ckeditor/",
        "assets/datatables/",
        "assets/datatables-bs/",
        "assets/fastclick/",
        "assets/flot/",
        "assets/font-awesome/",
        "assets/fullcalendar/",
        "assets/html5shiv/",
        "assets/icheck/",
        "assets/inputmask/",
        "assets/ion-rangeslider/",
        "assets/jquery-jeditable/",
        "assets/jquery-ui/",
        "assets/jquery-knob/",
        "assets/jquery-pjax/",
        "assets/jquery-mousewheel/",
        "assets/jquery-slimscroll/",
        "assets/jquery-sparkline/",
        "assets/jvectormap/",
        "assets/moment/",
        "assets/moment-timezone/",
        "assets/ionicons/",
        "assets/nestable/",
        "assets/morris.js/",
        "assets/pace/",
        "assets/raphael/",
        "assets/select2/",
        "assets/slimscroll/",
        "assets/toastr/",
        "assets/smartwizard/"
      ]
    }
  });

  // Load all grunt tasks

  // Copy All Files
  grunt.loadNpmTasks('grunt-contrib-copy');

  // Delete not needed files
  grunt.loadNpmTasks('grunt-contrib-clean');

  // The default task (running "grunt" in console) is "watch"
  grunt.registerTask('default', ['copy']);
};
