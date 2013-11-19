class User < ActiveRecord::Base
  # Include default devise modules. Others available are:
  # :confirmable, :lockable, :timeoutable and :omniauthable
  devise :database_authenticatable, :registerable,:omniauthable,
         :recoverable, :rememberable, :trackable, :validatable, :confirmable, :omniauth_providers => [:google_oauth2,:facebook]

  # Setup accessible (or protected) attributes for your model
  attr_accessible :email, :password, :password_confirmation, :remember_me,:profile_attributes,:provider, :uid,:oauth_token,:oauth_expires_at
  # attr_accessible :title, :body

 has_many :events,:dependent => :destroy
 has_one  :profile,:dependent => :destroy
 has_many :user_signed,:dependent => :destroy
 accepts_nested_attributes_for :profile
 


def self.from_omniauth(auth)

    #raise auth.credentials.token.inspect
    if user = User.find_by_email(auth.info.email)      
      user.provider = auth.provider
      #user.uid = auth.uid
      #user.oauth_token = auth.credentials.token
      #user.oauth_expires_at = Time.at(auth.credentials.expires_at)
      user
      
    else
      where(auth.slice(:provider, :uid)).first_or_create do |user|
        user.provider = auth.provider
        #user.uid = auth.uid
        #user.username = auth.info.name
        user.email = auth.info.email
        #user.oauth_token = auth.token
        #user.oauth_expires_at = auth.expires_at
        #user.password = 'nareshbabu'
        #user.avatar = auth.info.image
      end
    end
end







end
