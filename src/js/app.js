zo(function() {
    var racine = new zo,stop = {div : {class : "stop"}},link = document.location.href; link  = link.split('/');

    //{div : {class : "center_parent",child : []}}
    racine.squelette([
        {header : ""},
        {section : {child : [
            {div : {class : "center_parent",id : "content_parent_section",child : [
                {div : {id : "load_panel",child : [{img : {src : "src/media/icone/wait.gif"}},{span : {text : "Chargement en cours..."}}]}}
            ]}}
        ]}},
        {footer : ""},
    ],get("body"));

    window.onpopstate = function() {
        
    }
    wait("open",function(){
        control({data : {action : "session"},link : "user.php"},function(request) {
            var frame = [
                [
                   {h1 : {text : "Identifiez-vous"}},
                   {form : {action : "#",method : "post",id : "form",child : [
                        {label : {class : "conteneur_champ",child : [
                            {span : {text : "Utilisateur"}},
                            {input : {type : "text" , placeholder : "Utilisateur",name : "user_",class : "border_rond"}}
                        ]}},
                        {label : {class : "conteneur_champ",child : [
                            {span : {text : "Mot de passe"}},
                            {input : {type : "password" , placeholder : "Mot de passe",name : "secret_",class : "border_rond"}}
                        ]}},
                   ]}},
                   {div : {id : "send_login_btn",class : "border_rond",text : "S'identifier"}},
                ],
                [
                    {div : {id : "menu_block",child : [
                        {div : {class : "item_1",id : "menu",child : [{span : {class : "icon left",id : "icon_menu"}},{span : {class : "title left",text : "Menu"}},stop]}},
                        {a : {href : "add",class : "item_1",child : [{span : {class : "icon left", id : "collect"}},{span : {class : "title left",text : "Collecte donnée"}},stop]}},
                        {a : {href : "get",class : "item_1",child : [{span : {class : "icon left", id : 'consultation'}},{span : {class : "title left",text : "Consultation"}},stop]}},
                        {a : {href : "user",class : "item_1",child : [{span : {class : "icon left", id : "user"}},{span : {class : "title left",text : "Utilisateurs"}},stop]}},
                    ]}},
                    {div : {id : "displayer",class : "effet_panel",style : "height:"+($(window).height()-20)+"px",child : [
                        {h1 : {id : "title_current_item",text : ""}},
                        {div : {id : "displayer_content"}}
                    ]}}
                ]
            ],panel = "user_connect",a = true;
            if(!request.end){
                frame = frame[0];
            }else{
                frame = frame[1];
                panel = "user_espace";
            }
            panel_ = $("#"+panel);
            if(typeof panel_.attr("id") != "undefined"){
                if(panel != "user_espace"){
                    panel_.html(""); 
                }
                else{a = false;}
                
            }
            else{
                $("#content_parent_section").append($("<div id='"+panel+"'>").hide());
                panel_ = $("#"+panel);
            }
            if(a){
                racine.squelette(frame,get("#"+panel));
            }
            wait("close",function(){
                display_this(panel_,panel_.parent()); 
                if(panel == "user_connect"){
                    var rh = $(window).height() - 452,rh = (rh > 0)?rh/2:0;
                    panel_.css({marginTop : rh+"px"}).attr("class","effet_panel").parent().css({paddingBottom: rh+"px"});
                    
                    $("input").on("keydown",function(){$(this).attr("style","") ;});

                    $("#send_login_btn").on("click",function(){
                       var form = $("#form").find("input"),error = 0,data = {};
                       for(var t = 0; t < form.length; t++){
                           var n = form.eq(t).attr("name"),v = form.eq(t).val();
                           
                            if(empty(v)){form.eq(t).css({border : "1px solid #fb0000"});error++;}
                            else{data[n] = v;}
                       }
                       if(!error){
                           wait("open",function(){
                                data["action"] = "login";
                                control({data : data,link : "user.php"},function(req) {
                                    if(req.end){
                                        document.location.reload(true);
                                    }
                                    else{
                                        wait("close",function(){
                                            display_this(panel_,panel_.parent());
                                            for(var key in req.error){
                                                $("input[name='"+key+"']").css({border : "1px solid #fb0000"})
                                            }
                                        });
                                    }
                                });
                           },"Connexion en cours...");
                        }
                    });
                }     
                else{
                    $(".item_1").on("click",function(e){
                        e.preventDefault();
                        var text = $(this).find(".title").eq(0).text(),link = $(this).attr("href"),dpl_title = $("#title_current_item");
                        if (text != "Menu") {
                            dpl_title.html(text);
                            creat_interface(link);
                        }
                    });
                }          
            });
            
        })
    });
})
function wait(etat,j,t) {
    var panel_load = $("#load_panel"),parent = $("#content_parent_section"),children = parent.children();
    if(typeof etat != "undifined" && etat == "open"){
        panel_load.find("span").html((typeof t != "undefined")?t:"Chargement en cours...")
        for (let i = 0; i < children.length; i++) {children.eq(i).hide();}
        H = $(window).height() - $("header").height() - 5,h = (H-130)/2 ;
        parent.height(H); panel_load.css({marginTop : h+"px"}).fadeIn(function() {if(typeof j == "function"){j();}});
        
    }
    else{
        panel_load.fadeOut(function() {
            parent.attr("style","").show(function(){
                if(typeof j == "function"){j();}
            })
        });
    }
}
function loagin(parent,text,action){
    var racine = new zo,jk = $('#'+parent),kk = (jk.height()-130)/2;
    jk.html("");
    racine.squelette([
        {div : {id : "load_panel",style : "margin-top : "+kk+"px",child : [{img : {src : "src/media/icone/load.gif"}},{span : {text : (typeof text == "string")?text:"Chargement en cours..."}}]}}
    ],get("#"+parent));

}
function control(d,f){
    $.ajax({
        url : "param/"+d.link,
        type : "POST",
        data : (typeof d.data != "object")?{}:d.data,
        dataType : "JSON",
        success : function(ans){
            f(ans);
        }
    })
}
function display_this(p,f) {
    var c = f.children();
    for (let i = 0; i < c.length; i++) {c.eq(i).hide();}
    p.show();
}
function creat_interface(link) {
    loagin("displayer_content","Chargement en cours...");
    control({data : {action : link},link : "content.php"},function(a){
        if (a.end) {
            var all_interface = {
                "add" : [
                    {div : {id : "block1",class : "left",child : [
                        {h2 : {text : "Formulaire de collecte de donnée"}},
                        {div : {id : "conteneur_form",child : [
                            {label : {class : "conteneur_champ",child : [
                                {select : {type : "text" ,id : "denre",name : "denre",class : "border_rond",child : [
                                    {option : {text : "Denrées agricoles",value : ""}},                                   
                                ]}}
                            ]}},
                            {label : {class : "conteneur_champ",child : [
                                {select : {type : "text" ,id : "source",name : "source",class : "border_rond",child : [
                                    {option : {text : "sources d'approvisionnement",value : ""}},
                                ]}}
                            ]}},
                            {label : {class : "conteneur_champ",child : [
                                {select : {type : "text" ,id : "circuit",name : "circuit",class : "border_rond",child : [
                                    {option : {text : "Circuits d'acheminement",value : ""}}
                                ]}}
                            ]}},
                            {label : {class : "conteneur_champ",child : [
                                {input : {type : "text" , placeholder : "Date d'achat (jj/MM/AAAA)",name : "achat",class : "border_rond"}}
                            ]}},
                            {label : {class : "conteneur_champ",child : [
                                {input : {type : "text" , placeholder : "Date de livraison (jj/MM/AAAA)",name : "livraison",class : "border_rond"}}
                            ]}},
                            {div : {class : "submit_form right",for : "add_collect",text : "Ajouter"}},
                            stop
                        ]}}
                    ]}},
                    {div : {id : "block2",class : "left",child : [
                        {h3 : {text : "Liste de vos collectes"}}
                    ]}},
                    stop
                ],
                "get" : [
                    {div : {id : "list_all_collect",child : [
                        {h3 : {text : "Liste des données"}},
                         {div : {class : "every_detail",child : [
                             {h4 : {text : "Denré (Source)"}},
                             {b : {text : "Circut"}},
                             {ul : {child : [
                                 {li : {class : "left",text : "Date d'achat"}},
                                 {li : {class : "left",text : "Date de livraison"}},
                                 {li : {class : "left",text : "V1"}},
                                 {li : {class : "left",text : "V2"}},
                                 stop
                             ]}},
                             stop
                         ]}}
                    ]}}
                ],
                "user" : [
                    {div : {id : "block1",class : "left",child : [
                        {h2 : {text : "Ajouter un nouveau utilisateur"}},
                        {div : {id : "conteneur_form",child : [
                            
                            {label : {class : "conteneur_champ",child : [
                                {select : {type : "text" ,name : "",class : "border_rond",child : [
                                    {option : {text : "Type d'utilisateur",value : ""}}
                                ]}}
                            ]}},
                            {label : {class : "conteneur_champ",child : [
                                {input : {type : "text" , placeholder : "Identifiant",name : "",class : "border_rond"}}
                            ]}},
                            {label : {class : "conteneur_champ",child : [
                                {input : {type : "password" , placeholder : "Mot de passe",name : "",class : "border_rond"}}
                            ]}},
                        {div : {class : "submit_form right",for : "new_user",text : "Ajouter"}},
                        stop
                        ]}}
                    ]}},
                    {div : {id : "block2",class : "left",child : [
                        {h3 : {text : "Liste de vos utilisateurs"}}
                    ]}},
                    stop
                ]
            },interface = all_interface[a.pack], racine = new zo;
            $("#displayer_content").html("")
            racine.squelette(interface,get("#displayer_content"));
            var hhh = {"source" : ["Ferme","Plantation"],"denre" : ["Chou","Carotte","Orange"],"circuit" : ["Circuit 1","Circuit 2","Circuit 3"]};
            for(var ki in hhh){
                var b = hhh[ki];
                for (let ek = 0; ek < b.length; ek++) {
                    element = b[ek];
                    $("#"+ki).append($('<option value="'+element+'">').text(element))
                }
                
            }


            $(".submit_form").on("click",function(){
                var pp = $(this).parent(),j = ["input","select"],data = {},error = 0,fff = $(this).attr("for");
                for (let l = 0; l < j.length; l++) {
                    var ccc = pp.find(j[l]);
                    for(var t = 0; t < ccc.length; t++){
                        var n = ccc.eq(t).attr("name"),v = ccc.eq(t).val();
                        if(empty(v)){ccc.eq(t).css({border : "1px solid #fb0000"});error++;}
                        else{data[n] = v;}
                    }
                }
                if(!error){
                    data["action"] = fff;
                    control({data : data,link : "content.php"},function(req) {
                        
                    });
                 }
            })
        }
        else{document.location.reload(true);}
    });
}