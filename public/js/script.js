$(function () {

    //Permet d'adapter les sous catégories en fonction de la catégorie séléctionnée
    function adapaptOnChangeCategory() {

        let categorySelected = $('#form_new_ad_category option:selected').val();

            $.ajax({
                method: "POST",
                url: "/subcategory/bycategory/" + categorySelected,
                success: function (data) {
                    $("#form_new_ad_subCategory select").html(data);
                },
                error: function () {
                    alert("insertion impossible");
                }
            });
    }

    //Permet d'adapter les catégories affichées en fonction du type d'annonce sélectionné
    function adapaptOnChangeAdType() {
        let adTypeSelected = $('#form_new_ad_adType select').val();

        if (adTypeSelected == 1) { //adType == Don
            $('#form_new_ad_category option:last-of-type').attr('selected', false);
            $('#form_new_ad_duration').hide();
            $('#form_new_ad_conditionAd').show();
            $('#form_new_ad_category option').hide();
            $('#form_new_ad_category option').show();
            $('#form_new_ad_category option:last-of-type').hide();
            $('#form_new_ad_category option:nth(0)').attr('selected', true);

        } else if (adTypeSelected == 2) { //adType == Service
            $('#form_new_ad_category option:nth(0)').attr('selected', false);
            $('#form_new_ad_conditionAd').hide();
            $('#form_new_ad_duration').show();
            $('#form_new_ad_category option').hide();
            $('#form_new_ad_category option:last-of-type').show().attr('selected', true);

        }

        adapaptOnChangeCategory();
    }

    // *********************************** FORMULAIRE D'AJOUT D'ANNONCE ***********************************
    //CATEGORIES ET SOUSCATEGORIES

    $('#form_new_ad_adType select').change(function () {
        adapaptOnChangeAdType();
    });

    $('#form_new_ad_category select').change(function () {
        adapaptOnChangeCategory();
    });

    // *********************************** FORMULAIRE DE MODIFICATION D'ANNONCE ***********************************
    //si le code est éxécuté dans la page de modif d'annonce : on set par défaut les paramètres initiaux de l'annonce à modifier
    if ($(".hidden_inputs").length > 0) {

        $('#form_new_ad_adType option[value="' + $("#hidden_ad_type").val() + '"]').attr('selected', true);
        $('#form_new_ad_category option[value="' + $("#hidden_category").val() + '"]').attr('selected', true);
        $('#form_new_ad_subCategory option[value="' + $("#hidden_sub_category").val() + '"]').attr('selected', true);
        $('#form_new_ad_conditionAd option[value="' + $("#hidden_condition_ad").val() + '"]').attr('selected', true);

        if ($('#form_new_ad_adType select').val() == 1) {
            $('#form_new_ad_duration').hide();
            $('#form_new_ad_conditionAd').show();
        } else {
            $('#form_new_ad_duration').show();
            $('#form_new_ad_conditionAd').hide();
        }

    } else {
        //si le code est éxécuté dans la page d'ajout d'annonce : on set les paramètres par défaut
        adapaptOnChangeAdType();
        $('#form_new_ad_conditionAd option:nth(0)').hide();
        $('#form_new_ad_conditionAd option:nth(3)').attr('selected', true);
    }

    // --------------------- BARRE DE RECHERCHE DE VILLES DYNAMIQUE DES FORMS AJOUT ET MODIF ANNONCE
    //tuto JS pour la mise à jour de la div suggestion https://www.youtube.com/watch?v=L4t8aksnWt0
    $("#searchInput").keyup(function () {
        let input = $("#searchInput").val();

        //tuto Requête https://developer.mozilla.org/fr/docs/Learn/JavaScript/Objects/JSON
        let requestURL = 'https://geo.api.gouv.fr/communes?nom=' + input + '&boost=population&limit=6';
        let request = new XMLHttpRequest();
        request.open('GET', requestURL);
        request.responseType = 'json';
        request.send();
        request.onload = function () {
            let result = request.response;
            let suggestion = '';
            if (input != '') {
                $('.suggestions').show();
                result.forEach(resultItem =>
                    suggestion += ("<div class='suggestion'>" + resultItem.codesPostaux[0] + " - " + resultItem.nom + "</div>"));
            } else {
                //on cache la div de suggestions si l'utilisateur n'a rien écrit dans la barre de recherche
                $('.suggestions').hide();
            }
            $('.suggestions').html(suggestion);

            //SELECTION DE LA VILLE DANS LA BARRE DE RECHERCHE
            $('.suggestion').click(function () {
                $("#searchInput").val($(this).html());
                $('.suggestions').html('');
            })
        }
    });


});