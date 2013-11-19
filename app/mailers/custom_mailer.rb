class CustomMailer < ActionMailer::Base
  default from: "from@example.com"


 def welcome_email(user)
    @user = user    
    mail(to: @user.email,
         subject: 'Welcome') do |format|
      format.html { render 'confirmation_instructions' }
     
    end
  end
end
