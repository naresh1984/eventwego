class Profile < ActiveRecord::Base
  attr_accessible :mobile, :user_id

   belongs_to :user

  accepts_nested_attributes_for :user


  #model.errors.add(:field, nil)
  

  validates :mobile,
            :uniqueness=> true,
            :presence => true
                 
           
           
validates_format_of :mobile, 
      :with => /\(?[0-9]{3}\)?[0-9]{3}-[0-9]{4}/,
      :message => "numbers must be in (xxx)xxx-xxxx format."






end
