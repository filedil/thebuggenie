function findIdentifiable(url, field)
{
	var params = Form.serialize(field + '_form');
	new Ajax.Updater(field + '_results', url, {
	asynchronous: true,
	method: "post",
	parameters: params,
	onLoading: function () { $(field + '_spinning').show(); },
	onComplete: function () { $(field + '_spinning').hide(); }
	});
}

function showBud(elem_id)
{
	$('bud_' + elem_id).show();
	$('icon_' + elem_id).className = "imgtd_bud_hover";
}

function hideBud(elem_id)
{
	$('bud_' + elem_id).hide();
	$('icon_' + elem_id).className = "imgtd_bud";
}

tinyMCE.init({
	theme : "advanced",
	mode : "none",
	plugins : "bbcode",
	theme_advanced_buttons1 : "bold,italic,underline,forecolor,|,bullist,numlist,blockquote,|,undo,redo,|,link,unlink",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "bottom",
	theme_advanced_toolbar_align : "left",
	theme_advanced_styles : "Code=bb_code;Quote=bb_quote",
	entity_encoding : "raw",
	body_class: "tinymce_body",
	add_unload_trigger : false,
	remove_linebreaks : false
});

function getUserStateList()
{
	new Ajax.Updater('user_statelist', 'ajax_handler.php?getuserstatelist=true', {
	asynchronous:true,
	method: "post"
	});
}

function setUserState(sid)
{
	new Ajax.Request('ajax_handler.php?getuserstatelist=true', {
	asynchronous:true,
	method: "post",
	parameters: {setuserstate: sid}
	});
}

function setEmailPrivacy(priv)
{
	new Ajax.Updater('account_email', 'ajax_handler.php?setemailprivacy=true', {
	asynchronous:true,
	method: "post",
	parameters: {setting: priv}
	});
}

function showFollowUps(priv)
{
	new Ajax.Updater('account_followups', 'ajax_handler.php?showfollowups=true', {
	asynchronous:true,
	method: "post",
	parameters: {setting: priv}
	});
}

function showAssigned(priv)
{
	new Ajax.Updater('account_showassigned', 'ajax_handler.php?showassigned=true', {
	asynchronous:true,
	method: "post",
	parameters: {setting: priv}
	});
}

function submitNewPassword()
{
	var params = Form.serialize('changepassword_form');
	new Ajax.Updater('password_changed_span', 'ajax_handler.php?change_password=true', {
	asynchronous:true,
	method: "post",
	parameters: params,
	evalScripts: true
	});
	Element.show('password_changed_span');
}

function addFriend(uname, rndno, u_id)
{
	new Ajax.Updater('friends_message_' + uname + '_' + rndno, 'ajax_handler.php?addfriend=true', {
	asynchronous:true,
	method: "post",
	parameters: {uid: u_id},
	onSuccess: function (addFriendSuccess) {
		new Ajax.Updater('friends_link_' + uname + '_' + rndno, 'ajax_handler.php?getfriendlink=true', {
		asynchronous:true,
		method: "get",
		parameters: {uid: u_id, rnd_no: rndno}
		});
	}
	});
}

function removeFriend(uname, rndno, u_id)
{
	new Ajax.Updater('friends_message_' + uname + '_' + rndno, 'ajax_handler.php?removefriend=true', {
	asynchronous:true,
	method: "post",
	parameters: {uid: u_id},
	onSuccess: function (addFriendSuccess) {
		new Ajax.Updater('friends_link_' + uname + '_' + rndno, 'ajax_handler.php?getfriendlink=true', {
		asynchronous:true,
		method: "get",
		parameters: {uid: u_id, rnd_no: rndno}
		});
	}
	});
}

function addComment(modl, t_type, t_id)
{
	tinyMCE.triggerSave();
	var params = Form.serialize('new_comment_form_' + modl + '_' + t_type + '_' + t_id);
	new Ajax.Request('ajax_handler.php', {
	asynchronous: true,
	method: "post",
	parameters: params,
	onSuccess: function (something) {
		getComments(modl, t_type, t_id);
		Form.reset('new_comment_form_' + modl + '_' + t_type + '_' + t_id);
		Element.hide('addComment_' + modl + '_' + t_type + '_' + t_id);
		Element.show('addCommentLink_' + modl + '_' + t_type + '_' + t_id);
	},
	onFailure: function (somethingelse) {
		alert('Your comment could not be added. Please try again.');
	}
	});
}
function getComments(modl, t_type, t_id)
{
	new Ajax.Updater('comments_span_' + modl + '_' + t_type + '_' + t_id, 'ajax_handler.php?getcomments=true', {
	asynchronous:true,
	method: "post",
	evalScripts: true,
	parameters: { target_id: t_id, target_type: t_type, module: modl },
	insertion: Insertion.Top
	});
}

function editComment(cid, modl, t_type, t_id)
{
	new Ajax.Updater('comment_' + cid, 'ajax_handler.php?edit_comment=true', {
	asynchronous:true,
	method: "post",
	evalScripts: true,
	parameters: { comment_id: cid, target_id: t_id, target_type: t_type, module: modl } });
}

function getComment(cid, modl, t_type, t_id)
{
	new Ajax.Updater('comment_' + cid, 'ajax_handler.php?get_comment=true', {
	asynchronous:true,
	method: "post",
	evalScripts: true,
	parameters: { comment_id: cid, target_id: t_id, target_type: t_type, module: modl } });
}

function updateComment(cid, modl, t_type, t_id)
{
	tinyMCE.triggerSave();
	var params = Form.serialize('edit_comment_form_' + modl + '_' + t_type + '_' + t_id);
	new Ajax.Updater('comment_' + cid, 'ajax_handler.php?update_comment=true&comment_id=' + cid + '&target_id=' + t_id + '&target_type=' + t_type + '&module=' + modl, {
	asynchronous:true,
	method: "post",
	evalScripts: true,
	parameters: params,
	onSuccess: function (something) {
		tinyMCE.execCommand('mceRemoveControl', false, 'new_comment_comment_' + modl + '_' + t_type + '_' + t_id);
	},
	onFailure: function (somethingelse) {
		alert('Your comment could not be updated. Please try again.');
	}	
	});
}

function deleteComment(cid, modl, t_type, t_id)
{
	new Ajax.Request('ajax_handler.php?delete_comment=true', {
	asynchronous:true,
	method: "post",
	evalScripts: true,
	parameters: { comment_id: cid, target_id: t_id, target_type: t_type, module: modl } });
	Element.hide('commentheader_' + cid);
	Element.hide('commentbody_' + cid);
	Element.show('deletedcomment_' + cid);
}
