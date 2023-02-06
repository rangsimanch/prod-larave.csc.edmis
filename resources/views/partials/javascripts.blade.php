<!-- ColReorderWithResize JS -->
<script src="https://cdn.datatables.net/colreorder/1.5.2/js/dataTables.colReorder.js"></script>
<script>
    // $(function(){
    //     /** add active class and stay opened when selected */
    //     var url = window.location;

    //     // for sidebar menu entirely but not cover treeview
    //     $('ul.sidebar-menu a').filter(function() {
    //         return this.href == url;
    //     }).parent().addClass('active');

    //     $('ul.treeview-menu a').filter(function() {
    //         return this.href == url;
    //     }).parent().addClass('active');

    //     // for treeview
    //     $('ul.treeview-menu a').filter(function() {
    //          return this.href == url;
    //     }).parentsUntil('.sidebar-menu > .treeview-menu').addClass('menu-open').css('display', 'block');
    // });

    @can('construction_contract_select')
        $('#navbar__select-construction_contract').change(function(event) {
            $('#navbar__select-construction_contract-form').submit();
        });
    @endif
</script>


@yield('javascript')
