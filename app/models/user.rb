class User < ActiveRecord::Base
  # Include default devise modules. Others available are:
  # :confirmable, :lockable, :timeoutable and :omniauthable
  devise :database_authenticatable, :registerable,
         :recoverable, :rememberable, :trackable, :validatable ,:confirmable

  # Setup accessible (or protected) attributes for your model
  attr_accessible :email, :password, :password_confirmation, :remember_me,:profile_attributes
  # attr_accessible :title, :body

 has_many :events , :dependent => :destroy
 has_one  :profile, :dependent => :destroy
 has_many :user_signed, :dependent => :destroy
 accepts_nested_attributes_for :profile





end
