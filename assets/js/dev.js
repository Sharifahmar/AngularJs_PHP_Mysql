var dev = true;
function triggerDialog(msg, typ, loc) {

    BootstrapDialog.show({
        type: typ,
        title: 'Thingspeak cloud Response',
        message: '<h3>' + msg + '</h3>',
        closable: false,
        buttons: [{
                label: 'Close',
                cssClass: 'btn-danger',
                action: function (dialogRef) {
                    dialogRef.close();

                    if (typeof loc !== 'undefined') {
                        window.location.href = loc;
                    }

                    $('.save-blog').text('Submit');
                }
            }]
    });
}
function br2nl(str) {
    return str.replace(/<br\s*\/?>/mg, "\n");
}
var request = function (e) {
    /**/
    var $this = request.prototype;
    /**/
    $this.rUrl = '';
    /**/
    $this.rAction = '';
    /**/
    $this.rType = 'GET';
    /**/
    $this.pData = true;
    /**/
    $this.cType = 'application/x-www-form-urlencoded; charset=UTF-8';
    /**/
    $this.dType = 'json';
    /**/
    $this.isFIle = false;
    /**/
    $this.aData = new Object();
    /**/
    $this.apiRequest = (e == undefined ? false : e);
    /**/
    $this.callback = this.defaultSuccess;
    /**/
    $this.prepareRequest = function () {

        this.pData = !this.isFIle;
        this.cType = this.pData ? this.cType : this.pData;
        return this.dispatchRequest();
    };
    /**/
    $this.dispatchRequest = function () {
        var requestUrl = this.apiRequest ? this.rAction : this.rUrl + '?action=' + this.rAction;
//        console.log(requestUrl);
        $.ajax({
            url: requestUrl,
            type: this.rType,
            dataType: this.dType,
            data: this.aData,
            processData: this.pData,
            contentType: this.cType,
            success: this.callback,
            error: this.defaultError(),
            beforeSend: function () {
            }
        });
    };
    /**/
    $this.defaultSuccess = function () {
        return function (data) {};
    };
    /**/
    $this.defaultError = function () {
        return function (jqXHR, s, q, d) {

            var _res = jqXHR.responseJSON;
            if (_res.error)
                triggerDialog(_res.error, BootstrapDialog.TYPE_DANGER);
        };
    };
    /**/
    $this.init = function (m, a, d, c, f) {

        console.warn("init is deprecated");
        return 'DEPRECATED';

    };
    /**/
    $this.get = function (a, d, s, f) {
        this.rType = 'GET';
        this.rAction = (typeof a == 'undefined') ? this.rAction : a;
        this.aData = (typeof d == 'object') ? d : this.aData;
        this.callback = (typeof s == 'function') ? s : this.callback;
        this.isFIle = (typeof f == 'boolean' && f);
        return this.prepareRequest();
    };
    /**/
    $this.post = function (a, d, s, f) {

        this.rType = 'POST';
        this.rAction = (typeof a == 'undefined') ? this.rAction : a;
        this.aData = (typeof d == 'object') ? d : this.aData;
        this.callback = (typeof s == 'function') ? s : this.callback;
        this.isFIle = (typeof f == 'boolean' && f);
        return this.prepareRequest();
    };

    return this;
};
/**/

