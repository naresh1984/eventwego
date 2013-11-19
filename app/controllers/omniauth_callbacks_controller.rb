class OmniauthCallbacksController < Devise::OmniauthCallbacksController
  def google_oauth2
    user = User.from_omniauth(request.env["omniauth.auth"])   
    if user.persisted?
      flash.notice = "Signed in Through Google!"
      @user=User.find(user.id)
      @user.provider=user.provider
      #@user.uid=user.uid
      @user.update_attributes(params[:user]) 
      #@user.oauth_token = user.oauth_token
      #@user.oauth_expires_at = user.oauth_expires_at    
      sign_in_and_redirect user
    else
    pass=rand(10 ** 10)
    #raise pass.inspect;
    #:uid => user.uid,:oauth_token => user.oauth_token,:oauth_expires_at => user.oauth_expires_at
    user = User.new(:email => user.email,:password => pass ,:provider => user.provider) 
    user.skip_confirmation!
    user.save(:validate => false)
    signed = Profile.new(:user_id => user.id)
    signed.save(:validate => false)
    flash.notice = "Signed in Through Google!"
    #sign_up(resource_name, user)
    #send_admin_mail(user)
    sign_in_and_redirect user
    end
  end

def facebook
    user = User.from_omniauth(request.env["omniauth.auth"])   
    if user.persisted?
      flash.notice = "Signed in Through facebook!"
      @user=User.find(user.id)
      @user.provider=user.provider
      #@user.uid=user.uid
      @user.update_attributes(params[:user]) 
      sign_in_and_redirect user
    else
    pass=rand(10 ** 10)
    #raise pass.inspect; ,:uid => user.uid
    user = User.new(:email => user.email,:password => pass ,:provider => user.provider) 
    user.skip_confirmation!
    user.save(:validate => false)
    signed = Profile.new(:user_id => user.id)
    signed.save(:validate => false)
    flash.notice = "Signed in Through facebook!"
    #sign_up(resource_name, user)
    #send_admin_mail(user)
    sign_in_and_redirect user
    end
  end


end
